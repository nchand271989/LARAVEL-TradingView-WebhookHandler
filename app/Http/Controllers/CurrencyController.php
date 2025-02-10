<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        try {
            Log::info('Fetching currencies list', ['user' => $user->id, 'search' => $request->search]);

            $query = Currency::query();

            if ($request->has('search')) {
                Log::info('Applying search filter', ['user' => $user->id, 'search' => $request->search]);
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('shortcode', 'like', "%{$request->search}%");
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'shortcode', 'status', 'created_at'])) {
                Log::info('Applying sorting', ['user' => $user->id, 'sortBy' => $request->sortBy, 'sortOrder' => $request->sortOrder]);
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                Log::info('Applying default sorting by created_at desc', ['user' => $user->id]);
                $query->orderBy('created_at', 'desc');
            }

            $currencies = $query->paginate(10);
            Log::info('Currencies fetched successfully', ['user' => $user->id, 'count' => $currencies->count()]);
        } finally {
            DB::disconnect('mysql');
            Log::info('Database connection closed after fetching currencies', ['user' => $user->id]);
        }

        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        $user = Auth::user();
        Log::info('Navigating to currency creation page', ['user' => $user->id]);
        return view('currencies.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:10|unique:currencies,shortcode',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Log::info('Creating a new currency', ['user' => $user->id, 'name' => $request->name, 'shortcode' => $request->shortcode]);
            $currency = Currency::create([
                'name' => $request->name,
                'shortcode' => $request->shortcode,
                'status' => $request->status,
                'createdBy' => $user->id,
                'lastUpdatedBy' => $user->id,
            ]);
            Log::info('Currency created successfully', ['user' => $user->id, 'currency_id' => $currency->id]);
        } finally {
            DB::disconnect('mysql');
            Log::info('Database connection closed after currency creation', ['user' => $user->id]);
        }

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function toggleStatus($curid)
    {
        $user = Auth::user();
        try {
            Log::info('Fetching currency for status toggle', ['user' => $user->id, 'currency_id' => $curid]);
            $currency = Currency::findOrFail($curid);

            $currency->status = ($currency->status === 'Active') ? 'Inactive' : 'Active';
            $currency->save();
            Log::info('Currency status toggled', ['user' => $user->id, 'currency_id' => $curid, 'new_status' => $currency->status]);

            if ($currency->status === 'Inactive') {
                $currency->exchanges()->detach();
                Log::info('Removed currency from all exchanges', ['user' => $user->id, 'currency_id' => $curid]);
            }
        } finally {
            DB::disconnect('mysql');
            Log::info('Database connection closed after toggling status', ['user' => $user->id]);
        }

        return redirect()->route('currencies.index')->with('success', 'Currency status updated.');
    }

    public function destroy($curid)
    {
        $user = Auth::user();
        try {
            Log::info('Deleting currency', ['user' => $user->id, 'currency_id' => $curid]);
            $currency = Currency::findOrFail($curid);
            $currency->delete();
            Log::info('Currency deleted successfully', ['user' => $user->id, 'currency_id' => $curid]);
        } finally {
            DB::disconnect('mysql');
            Log::info('Database connection closed after deletion', ['user' => $user->id]);
        }

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
