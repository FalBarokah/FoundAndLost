<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Report;

class AdminClaimController extends Controller
{
    public function tindakLanjuti(int $id)
    {
        $claim = Claim::with('report')->findOrFail($id);

        $claim->update(['status' => 'ditindaklanjuti']);

        // When claim is approved, hide report from public catalog and move to handover queue
        $claim->report->update([
            'status' => 'siap_diambil',
            'is_published' => false,
        ]);

        return back()->with('success', "Klaim {$claim->claim_code} telah disetujui untuk ditindaklanjuti. Laporan sekarang masuk antrean Serah Terima.");
    }

    public function batalkan(int $id)
    {
        $claim = Claim::with('report')->findOrFail($id);

        $claim->update(['status' => 'dibatalkan']);

        // Check if there are other approved claims for this report
        $hasOtherActiveClaim = Claim::where('report_id', $claim->report_id)
            ->where('status', 'ditindaklanjuti')
            ->exists();

        if (! $hasOtherActiveClaim && $claim->report->status !== 'selesai') {
            $claim->report->update(['is_published' => true]);
        }

        return back()->with('success', "Klaim {$claim->claim_code} telah dibatalkan.");
    }
}
