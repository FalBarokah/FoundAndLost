<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'hilang');
        if (! in_array($type, ['hilang', 'ditemukan'])) {
            $type = 'hilang';
        }

        // Stats
        $totalReports = Report::count();
        // Siap diserahkan: reports in handover queue (approved claims) + hilang items with status siap_diambil
        $readyToHandover = Report::where('status', 'siap_diambil')
            ->where(function ($q) {
                $q->where('type', 'hilang')
                    ->orWhereHas('claims', function ($cq) {
                        $cq->where('status', 'ditindaklanjuti');
                    });
            })
            ->count();
        $completedReports = Report::where('status', 'selesai')->count();

        // Reports query for dashboard (only ongoing reports, not selesai)
        $reports = Report::with('claims')
            ->where('type', $type)
            ->where('status', '!=', 'selesai')
            ->latest('incident_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact('reports', 'type', 'totalReports', 'readyToHandover', 'completedReports'));
    }
}
