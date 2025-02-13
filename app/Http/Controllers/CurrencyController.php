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
        return fetchFilteredRecords(Currency::class, $request, ['name', 'shortcode', 'status', 'created_at'], 'currencies.index');
    }

    /** Show the form for creating a new currency. */
    public function create()
    {
        return view('currencies.create');
    }

    /** Store a newly created currency in storage. */
    public function store(Request $request)
    {
        $requestID = generate_snowflake_id();                                                                   /** Unique log id to indetify request flow */

        logger()->info($requestID.'-> Requested to create new curency', ['request' => $request]);               /** Logging -> currency creation request*/

        $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:10|unique:currencies,shortcode',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Currency::create([
                'name' => $request->name,                                                                       /** Currency name */ 
                'shortcode' => $request->shortcode,                                                             /** Currency short code */ 
                'createdBy' => Auth::id(),                                                                      /** Currency created by user */ 
                'lastUpdatedBy' => Auth::id(),                                                                  /** Currency last updated by user */ 
                'status' => $request->status,                                                                   /** Currency status */ 
            ]);

            logger()->info($requestID.'-> New currency created.', ['request' => $request]);                     /** Logging -> exchange creation request*/
        } catch (\Exception $e) {

            /** Logging */
            logger()->error($requestID.'-> Failed to create currency', ['error' => $e->getMessage()]);

            return back()->with('error', 'An error occurred while saving the currency.');
        }

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

}
