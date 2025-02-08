<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Currency::query();

            if ($request->has('search')) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('shortcode', 'like', "%{$request->search}%");
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'shortcode', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $currencies = $query->paginate(10);
        } finally {
            DB::disconnect('mysql'); // Close DB connection after fetching data
        }

        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:10|unique:currencies,shortcode',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Currency::create([
                'name' => $request->name,
                'shortcode' => $request->shortcode,
                'status' => $request->status,
                'createdBy' => Auth::id(),
            ]);
        } finally {
            DB::disconnect('mysql'); // Ensure DB connection is closed
        }

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function toggleStatus($curid)
    {
        try {
            $currency = Currency::findOrFail($curid);
            $currency->status = ($currency->status === 'Active') ? 'Inactive' : 'Active';
            $currency->save();
        } finally {
            DB::disconnect('mysql'); // Close DB connection after update
        }

        return redirect()->route('currencies.index')->with('success', 'Currency status updated.');
    }

    public function destroy($curid)
    {
        try {
            $currency = Currency::findOrFail($curid);
            $currency->delete();
        } finally {
            DB::disconnect('mysql'); // Close DB connection after deletion
        }

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
