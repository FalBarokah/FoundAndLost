<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Handover;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $pendingHandovers = Handover::with(['report', 'claim', 'admin'])
            ->where('status', Handover::STATUS_MENUNGGU_VERIFIKASI)
            ->latest('handover_date')
            ->paginate(10);

        return view('admin.verification.index', compact('pendingHandovers'));
    }

    public function show(int $id)
    {
        $handover = Handover::with(['report.claims', 'claim', 'admin'])->findOrFail($id);

        return view('admin.verification.show', compact('handover'));
    }

    public function approve(Request $request, int $id)
    {
        $handover = Handover::with('report')->findOrFail($id);

        $handover->update([
            'status' => Handover::STATUS_DISETUJUI,
            'verified_by' => Auth::guard('admin')->id(),
            'verified_at' => Carbon::now(),
            'rejection_reason' => null,
        ]);

        $handover->report->update([
            'status' => 'selesai',
            'is_published' => false,
        ]);

        return redirect()->route('admin.history.index')
            ->with('success', "Laporan serah terima untuk {$handover->report->item_name} ({$handover->report->code}) berhasil disetujui. Laporan kini telah selesai dan masuk ke History.");
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $handover = Handover::with('report')->findOrFail($id);

        $handover->update([
            'status' => Handover::STATUS_DITOLAK,
            'rejection_reason' => $validated['rejection_reason'],
            'verified_by' => Auth::guard('admin')->id(),
            'verified_at' => Carbon::now(),
        ]);

        // Kembalikan status laporan ke 'siap_diambil' agar kembali ke halaman serah terima satpam
        $handover->report->update([
            'status' => 'siap_diambil',
            'is_published' => false,
        ]);

        return redirect()->route('admin.verification.index')
            ->with('success', "Laporan serah terima untuk {$handover->report->item_name} ({$handover->report->code}) telah ditolak dan dikembalikan ke halaman Serah Terima Satpam.");
    }
}
