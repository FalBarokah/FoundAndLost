<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query()->where('is_published', true);

        // Filter by type
        $type = $request->get('type', 'semua');
        if (in_array($type, ['hilang', 'ditemukan'])) {
            $query->where('type', $type);
        }

        // Search by item name or location
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $reports = $query->latest('incident_date')->paginate(12)->withQueryString();

        return view('home', compact('reports', 'type'));
    }
}
