<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class AdminHistoryController extends Controller
{
    public function index()
    {
        $histories = Report::with(['handover.admin'])
            ->where('status', 'selesai')
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.history.index', compact('histories'));
    }

    public function show(int $id)
    {
        $report = Report::with(['handover.admin', 'claims'])
            ->where('status', 'selesai')
            ->findOrFail($id);

        return view('admin.history.show', compact('report'));
    }
}
