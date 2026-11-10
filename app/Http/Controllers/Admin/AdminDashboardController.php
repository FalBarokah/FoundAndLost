<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Handover;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $isSuper = $user && $user->isSuperAdmin();

        $type = $request->get('type', 'hilang');
        if (! in_array($type, ['hilang', 'ditemukan'])) {
            $type = 'hilang';
        }

        // Stats
        $totalReports = Report::count();
        $readyToHandover = Report::where('status', 'siap_diambil')
            ->where(function ($q) {
                $q->where('type', 'hilang')
                    ->orWhereHas('claims', function ($cq) {
                        $cq->where('status', 'ditindaklanjuti');
                    });
            })
            ->count();
        $pendingVerificationCount = Handover::where('status', Handover::STATUS_MENUNGGU_VERIFIKASI)->count();
        $completedReports = Report::where('status', 'selesai')->count();
        $totalSatpam = Admin::where('role', Admin::ROLE_SATPAM)->count();

        // Data khusus Admin
        $pendingHandovers = collect();
        $recentCompletedHandovers = collect();
        if ($isSuper) {
            $pendingHandovers = Handover::with(['report', 'claim', 'admin'])
                ->where('status', Handover::STATUS_MENUNGGU_VERIFIKASI)
                ->latest('handover_date')
                ->take(5)
                ->get();

            $recentCompletedHandovers = Handover::with(['report', 'admin', 'verifier'])
                ->where('status', Handover::STATUS_DISETUJUI)
                ->latest('verified_at')
                ->take(5)
                ->get();
        }

        // Reports query for dashboard (khusus Satpam)
        $reports = Report::with('claims')
            ->where('type', $type)
            ->where('status', '!=', 'selesai')
            ->latest('incident_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'reports',
            'type',
            'totalReports',
            'readyToHandover',
            'pendingVerificationCount',
            'completedReports',
            'totalSatpam',
            'pendingHandovers',
            'recentCompletedHandovers'
        ));
    }
}
