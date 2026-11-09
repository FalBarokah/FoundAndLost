<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    public function create(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        if ($report->type !== 'ditemukan') {
            return redirect()->route('report.show', ['code' => $report->code])
                ->with('error', 'Hanya barang temuan yang dapat diklaim.');
        }

        return view('claims.create', compact('report'));
    }

    public function store(Request $request, string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'claimant_name' => 'required|string|max:100',
            'claimant_phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'proof_description' => 'required|string',
            'proof_photo' => 'nullable|image|max:2048',
        ], [
            'claimant_phone.regex' => 'Nomor telepon harus berupa angka antara 10 hingga 13 digit.',
        ]);

        do {
            $num = str_pad((string) mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $year = date('Y');
            $claimCode = "KLM-{$year}-{$num}";
        } while (Claim::where('claim_code', $claimCode)->exists());

        $proofPhotoUrl = null;
        if ($request->hasFile('proof_photo')) {
            $path = $request->file('proof_photo')->store('claims', 'public');
            $proofPhotoUrl = Storage::url($path);
        }

        $claim = Claim::create([
            'claim_code' => $claimCode,
            'report_id' => $report->report_id,
            'claimant_name' => $validated['claimant_name'],
            'claimant_phone' => $validated['claimant_phone'],
            'proof_description' => $validated['proof_description'],
            'proof_photo_url' => $proofPhotoUrl,
            'status' => 'menunggu',
        ]);

        return redirect()->route('claim.success', ['claim_code' => $claim->claim_code]);
    }

    public function success(string $claim_code)
    {
        $claim = Claim::with('report')->where('claim_code', $claim_code)->firstOrFail();

        return view('claims.success', compact('claim'));
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'claim_code' => 'required|string',
        ]);

        $claimCode = strtoupper(trim($request->claim_code));
        $claim = Claim::with('report')->where('claim_code', $claimCode)->first();

        if (! $claim) {
            return back()->with('claim_error', "Klaim dengan Nomor '{$claimCode}' tidak ditemukan.")->withInput();
        }

        return view('claims.status', compact('claim'));
    }
}
