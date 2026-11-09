<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function createHilang()
    {
        return view('reports.create', ['type' => 'hilang']);
    }

    public function createDitemukan()
    {
        return view('reports.create', ['type' => 'ditemukan']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:hilang,ditemukan',
            'item_name' => 'required|string|max:150',
            'description' => 'required|string',
            'special_features' => 'nullable|string',
            'location' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'photo' => 'nullable|image|max:2048',
        ], [
            'reporter_phone.regex' => 'Nomor telepon harus berupa angka antara 10 hingga 13 digit.',
        ]);

        // Generate unique matching FL and ED codes
        do {
            $num = str_pad((string) mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $year = date('Y');
            $code = "FL-{$year}-{$num}";
            $editCode = "ED-{$year}-{$num}";
        } while (Report::where('code', $code)->orWhere('edit_code', $editCode)->exists());

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
            $photoUrl = Storage::url($path);
        }

        $defaultStatus = $validated['type'] === 'hilang' ? 'proses_pencarian' : 'siap_diambil';

        $report = Report::create([
            'code' => $code,
            'edit_code' => $editCode,
            'type' => $validated['type'],
            'item_name' => $validated['item_name'],
            'description' => $validated['description'],
            'special_features' => $validated['special_features'] ?? null,
            'location' => $validated['location'],
            'incident_date' => $validated['incident_date'],
            'photo_url' => $photoUrl,
            'reporter_name' => $validated['reporter_name'],
            'reporter_phone' => $validated['reporter_phone'],
            'status' => $defaultStatus,
            'is_published' => true,
        ]);

        return redirect()->route('report.success', ['code' => $report->code]);
    }

    public function success(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        return view('reports.success', compact('report'));
    }

    public function show(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        return view('reports.show', compact('report'));
    }

    public function check(Request $request)
    {
        $tab = $request->get('tab', 'lacak');

        return view('reports.check', compact('tab'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $report = Report::where('code', $code)->first();

        if (! $report) {
            return back()->with('track_error', "Laporan dengan Kode Lacak '{$code}' tidak ditemukan.")->withInput();
        }

        return redirect()->route('report.show', ['code' => $report->code]);
    }

    public function editForm(Request $request)
    {
        $request->validate([
            'edit_code' => 'required|string',
        ]);

        $editCode = strtoupper(trim($request->edit_code));
        $report = Report::where('edit_code', $editCode)->first();

        if (! $report) {
            return back()->with('edit_error', "Laporan dengan Kode Edit '{$editCode}' tidak ditemukan.")->withInput();
        }

        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'edit_code' => 'required|string',
            'item_name' => 'required|string|max:150',
            'location' => 'required|string|max:255',
            'reporter_phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'description' => 'required|string',
            'special_features' => 'nullable|string',
        ], [
            'reporter_phone.regex' => 'Nomor telepon harus berupa angka antara 10 hingga 13 digit.',
        ]);

        if (strtoupper(trim($validated['edit_code'])) !== $report->edit_code) {
            return back()->withErrors(['edit_code' => 'Kode Edit tidak valid.'])->withInput();
        }

        $report->update([
            'item_name' => $validated['item_name'],
            'location' => $validated['location'],
            'reporter_phone' => $validated['reporter_phone'],
            'description' => $validated['description'],
            'special_features' => $validated['special_features'] ?? $report->special_features,
        ]);

        return redirect()->route('report.show', ['code' => $report->code])
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Request $request, string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        $request->validate([
            'edit_code' => 'required|string',
        ]);

        if (strtoupper(trim($request->edit_code)) !== $report->edit_code) {
            return back()->withErrors(['edit_code' => 'Kode Edit tidak valid.']);
        }

        $report->delete();

        return redirect()->route('home')->with('success', 'Laporan berhasil dihapus.');
    }
}
