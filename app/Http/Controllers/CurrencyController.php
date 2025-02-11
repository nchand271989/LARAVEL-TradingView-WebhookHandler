<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    /** Display a listing of the currencies.*/
    public function index(Request $request)
    {
        try {
            $query = Currency::query();

            /** Apply search filters if present */ 
            if ($request->has('search')) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('shortcode', 'like', "%{$request->search}%");
            }

            /** Apply sorting if valid parameters are provided */ 
            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'shortcode', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder === 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $currencies = $query->paginate(10);                                                     // Paginate results
        } catch (\Exception $e) {

            /** Logging */
            logger()->error('Failed to fetch currencies', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);

            return back()->with('error', 'An error occurred while fetching currencies.');
        }

        return view('currencies.index', compact('currencies'));
    }

    /** Show the form for creating a new currency. */
    public function create()
    {
        return view('currencies.create');
    }

    /** Store a newly created currency in storage. */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:10|unique:currencies,shortcode',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Currency::create([
                'name' => $request->name,                                                           // Currency name
                'shortcode' => $request->shortcode,                                                 // Currency short code
                'createdBy' => Auth::id(),                                                          // Currency created by user
                'lastUpdatedBy' => Auth::id(),                                                      // Currency last updated by user
                'status' => $request->status,                                                       // Currency status
            ]);
        } catch (\Exception $e) {

            /** Logging */
            logger()->error('Failed to save currency', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);

            return back()->with('error', 'An error occurred while saving the currency.');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    /** Toggle the status of a currency. */
    public function toggleStatus($curid)
    {
        try {
            DB::beginTransaction();                                                                 // Start Transaction

            $currency = Currency::findOrFail($curid);

            // Toggle status
            $currency->status = ($currency->status === 'Active') ? 'Inactive' : 'Active';
            $currency->save();


            // Detach from exchanges if deactivated
            if ($currency->status === 'Inactive') {
                $currency->exchanges()->detach();
            }

            DB::commit();                                                                           // Commit Transaction
        } catch (\Exception $e) {

            DB::rollBack();                                                                         // Rollback on failure

            /** Logging */
            logger()->error('Failed to toggle currency status', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            
            return back()->with('error', 'An error occurred while updating currency status.');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency status updated successfully.');
    }
}
