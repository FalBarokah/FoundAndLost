<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Handover;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminHandoverController extends Controller
{
    public function index()
    {
        // 1. Antrean siap diproses (hilang: siap_diambil, ditemukan: klaim ditindaklanjuti)
        // Tidak termasuk yang sedang berstatus 'menunggu_verifikasi'
        $queueReports = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }, 'handover'])
            ->where('status', 'siap_diambil')
            ->where(function ($query) {
                $query->whereHas('claims', function ($cq) {
                    $cq->where('status', 'ditindaklanjuti');
                })->orWhere('type', 'hilang');
            })
            ->latest('updated_at')
            ->paginate(10, ['*'], 'queue_page');

        // 2. Laporan yang sudah diajukan serah terima dan menunggu verifikasi Admin
        $pendingVerificationReports = Report::with(['claims', 'handover.admin'])
            ->where('status', 'menunggu_verifikasi')
            ->latest('updated_at')
            ->get();

        // 3. Laporan yang serah terimanya ditolak oleh Admin (perlu revisi / diproses ulang)
        $rejectedReports = Report::with(['claims', 'handover.admin'])
            ->where('status', 'siap_diambil')
            ->whereHas('handover', function ($hq) {
                $hq->where('status', Handover::STATUS_DITOLAK);
            })
            ->latest('updated_at')
            ->get();

        return view('admin.handover.index', compact('queueReports', 'pendingVerificationReports', 'rejectedReports'));
    }

    public function create(int $id)
    {
        $report = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }, 'handover'])->findOrFail($id);

        $activeClaim = $report->claims->first();
        $existingHandover = $report->handover;

        return view('admin.handover.create', compact('report', 'activeClaim', 'existingHandover'));
    }

    public function store(Request $request, int $id)
    {
        $report = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }, 'handover'])->findOrFail($id);

        $existingHandover = $report->handover;

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'recipient_contact' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'proof_photo' => 'nullable|image|max:2048',
        ]);

        $proofPhotoUrl = null;
        if ($request->hasFile('proof_photo')) {
            $path = $request->file('proof_photo')->store('handovers', 'public');
            $proofPhotoUrl = Storage::url($path);
        }

        $activeClaim = $report->claims->first();

        if ($existingHandover) {
            $existingHandover->update([
                'claim_id' => $activeClaim?->claim_id,
                'admin_id' => Auth::guard('admin')->id(),
                'recipient_name' => $validated['recipient_name'],
                'recipient_contact' => $validated['recipient_contact'],
                'proof_photo_url' => $proofPhotoUrl ?? $existingHandover->proof_photo_url,
                'notes' => $validated['notes'] ?? null,
                'handover_date' => Carbon::now(),
                'status' => Handover::STATUS_MENUNGGU_VERIFIKASI,
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);
        } else {
            Handover::create([
                'report_id' => $report->report_id,
                'claim_id' => $activeClaim?->claim_id,
                'admin_id' => Auth::guard('admin')->id(),
                'recipient_name' => $validated['recipient_name'],
                'recipient_contact' => $validated['recipient_contact'],
                'proof_photo_url' => $proofPhotoUrl,
                'notes' => $validated['notes'] ?? null,
                'handover_date' => Carbon::now(),
                'status' => Handover::STATUS_MENUNGGU_VERIFIKASI,
            ]);
        }

        // Laporan beralih ke status menunggu verifikasi
        $report->update([
            'status' => 'menunggu_verifikasi',
            'is_published' => false,
        ]);

        return redirect()->route('admin.handover.index')
            ->with('success', "Laporan serah terima untuk {$report->item_name} ({$report->code}) berhasil diajukan dan sedang menunggu verifikasi dari Admin.");
    }

    public function cancelQueue(int $id)
    {
        $report = Report::with(['claims', 'handover'])->findOrFail($id);

        // Cancel approved claims back to menunggu
        foreach ($report->claims as $claim) {
            if ($claim->status === 'ditindaklanjuti') {
                $claim->update(['status' => 'menunggu']);
            }
        }

        // Jika ada draft / rejected handover, hapus atau bersihkan
        if ($report->handover && $report->handover->status !== Handover::STATUS_DISETUJUI) {
            $report->handover->delete();
        }

        $report->update([
            'status' => $report->type === 'hilang' ? 'proses_pencarian' : 'siap_diambil',
            'is_published' => true,
        ]);

        return back()->with('success', 'Tindak lanjut serah terima telah dibatalkan. Laporan kembali aktif di katalog/dashboard.');
    }
}
