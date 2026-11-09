<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
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
        // Reports ready for handover (status != selesai)
        // 1. Found items with approved claim (status == 'ditindaklanjuti')
        // 2. Lost items with status == 'siap_diambil'
        $queueReports = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }])
            ->where('status', '!=', 'selesai')
            ->where(function ($query) {
                $query->whereHas('claims', function ($cq) {
                    $cq->where('status', 'ditindaklanjuti');
                })->orWhere(function ($hq) {
                    $hq->where('type', 'hilang')->where('status', 'siap_diambil');
                });
            })
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.handover.index', compact('queueReports'));
    }

    public function create(int $id)
    {
        $report = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }])->findOrFail($id);

        $activeClaim = $report->claims->first();

        return view('admin.handover.create', compact('report', 'activeClaim'));
    }

    public function store(Request $request, int $id)
    {
        $report = Report::with(['claims' => function ($q) {
            $q->where('status', 'ditindaklanjuti');
        }])->findOrFail($id);

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

        Handover::create([
            'report_id' => $report->report_id,
            'claim_id' => $activeClaim?->claim_id,
            'admin_id' => Auth::guard('admin')->id(),
            'recipient_name' => $validated['recipient_name'],
            'recipient_contact' => $validated['recipient_contact'],
            'proof_photo_url' => $proofPhotoUrl,
            'notes' => $validated['notes'] ?? null,
            'handover_date' => Carbon::now(),
        ]);

        // Once handover is created, status becomes 'selesai' and report moves to History!
        $report->update([
            'status' => 'selesai',
            'is_published' => false,
        ]);

        return redirect()->route('admin.history.index')
            ->with('success', "Laporan serah terima untuk {$report->item_name} ({$report->code}) berhasil dibuat. Laporan telah dipindahkan ke History.");
    }

    public function cancelQueue(int $id)
    {
        $report = Report::with('claims')->findOrFail($id);

        // Cancel approved claims back to menunggu
        foreach ($report->claims as $claim) {
            if ($claim->status === 'ditindaklanjuti') {
                $claim->update(['status' => 'menunggu']);
            }
        }

        $report->update([
            'status' => $report->type === 'hilang' ? 'proses_pencarian' : 'siap_diambil',
            'is_published' => true,
        ]);

        return back()->with('success', 'Tindak lanjut serah terima telah dibatalkan. Laporan kembali aktif di katalog/dashboard.');
    }
}
