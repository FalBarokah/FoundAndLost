<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function show(int $id)
    {
        $report = Report::with(['claims', 'handover'])->findOrFail($id);

        if ($report->type === 'hilang') {
            return view('admin.reports.show_hilang', compact('report'));
        }

        return view('admin.reports.show_ditemukan', compact('report'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:proses_pencarian,siap_diambil',
        ]);

        $report->update(['status' => $validated['status']]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}
