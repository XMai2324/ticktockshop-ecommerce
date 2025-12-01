<?php

namespace App\Http\Controllers;

use App\Models\ImportHistory;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function history(Request $request)
    {
        $query = ImportHistory::with('product')->orderBy('created_at', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $history = $query->paginate(20);

        return view('admin.importHistory', compact('history'));
    }
}
