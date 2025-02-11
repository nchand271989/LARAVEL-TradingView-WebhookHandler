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
        
            $query = Currency::query();

            if ($request->has('search')) {
                Log::info('Applying search filter - '. $request->search);
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('shortcode', 'like', "%{$request->search}%");
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'shortcode', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $currencies = $query->paginate(10);
            Log::info('Total found currencies - '. $currencies->count(). ' ['.$currencies->pluck('name')->implode(', ').']');
        } catch (\Exception $e) {
            Log::error('Failed to fetch currencies - '.$e->getMessage(), ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch exchanges.');
        } finally {
            DB::disconnect('mysql');
        }

        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        $user = Auth::user();
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
            Log::info('Creating a new currency '.$request->name.' ('.$request->shortcode.')', ['name' => $request->name, 'shortcode' => $request->shortcode]);
            $currency = Currency::create([
                'name' => $request->name,
                'shortcode' => $request->shortcode,
                'status' => $request->status,
                'createdBy' => $user->id,
                'lastUpdatedBy' => $user->id,
            ]);
            Log::info('Currency name '.$request->name.' created successfully with currencyId '.$currency->id, ['currency_id' => $currency->id, 'name' => $request->name]);
        } finally {
            DB::disconnect('mysql');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function toggleStatus($curid)
    {
        $user = Auth::user();
        try {
            $currency = Currency::findOrFail($curid);

            $currency->status = ($currency->status === 'Active') ? 'Inactive' : 'Active';
            $currency->save();
            Log::info('Currency status for currencyId '.$curid.', '.$currency->name.' toggled to '.$currency->status, ['currency_id' => $currency->id, 'status' => $currency->status]);

            if ($currency->status === 'Inactive') {
                $currency->exchanges()->detach();
                Log::info('Currency status for currencyId '.$curid.', '.$currency->name.' toggled to Inactive, resulting to deatach currency from all exchanges');
            }
        } finally {
            DB::disconnect('mysql');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency status updated.');
    }

    public function destroy($curid)
    {
        $user = Auth::user();
        try {
            $currency = Currency::findOrFail($curid);
            $currency->delete();
            Log::info('Deleting currency for curid - '.$curid, ['currency_id' => $curid]);
        } finally {
            DB::disconnect('mysql');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
