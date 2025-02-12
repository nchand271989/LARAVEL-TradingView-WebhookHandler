<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\ExchangeWallet;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    /** Display a listing of the exchanges.*/
    public function index(Request $request)
    {
        return fetchFilteredRecords(Exchange::class, $request, ['name', 'status', 'created_at'], 'exchanges.index', ['currencies']);    
    }

    /** Show the form for creating a new exchange. */
    public function create()
    {
        return $this->handleExchangeForm();
    }

    /** Show the form for editing of exchange selected. */
    public function edit(Exchange $exchange)
    {
        return $this->handleExchangeForm($exchange);
    }

    private function handleExchangeForm(?Exchange $exchange = null)
    {
        try {

            $currencies = Currency::where('status', 'Active')->get();
            return view('exchanges.create', compact('currencies', 'exchange'));

        } catch (\Exception $e) {

            logger()->error('Failed to load create/edit exchange form', ['error' => $e->getMessage()]);         /** Logging -> error to load exchange form */

            return back()->with('error', 'Failed to load create/edit exchange form.');
        }
    }

    /** Create new exchange by storing information in database. */
    public function store(Request $request)
    {
        $requestID = generate_snowflake_id();                                                                   /** Unique log id to indetify request flow */
        
        logger()->info($requestID.'-> Requested to create new exchange', ['request' => $request]);              /** Logging -> exchange creation request*/

        try {

            DB::beginTransaction();

            $exchange = Exchange::create([
                'name' => $request->name,                                                                       /** Exchange name */ 
                'createdBy' => Auth::id(),                                                                      /** Exchange created by user */
                'lastUpdatedBy' => Auth::id(),                                                                  /** Exchange last updated by user */
                'status' => $request->status,                                                                   /** Exchange status i.e Active/Inactive */ 
            ]);

            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);                                          /** Attach selected currencies with exchange */
            }
 
            DB::commit();

            logger()->info($requestID.'-> New exchange Created', ['request' => $request]);                      /** Logging -> exchange created. */

        } catch (\Exception $e) {

            DB::rollBack();
            
            logger()->error($requestID.'-> Failed to create exchange', ['error' => $e->getMessage()]);          /** Logging -> exchange creation error */
            
            return back()->with('error', 'Failed to create exchange: ' . $e->getMessage());

        }

        return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');         
    }

    public function update(Request $request, Exchange $exchange)
    {
        logger()->info('Requst for updating exchange', ['request' => $request->all()]);

        try {

            $request->validate([
                'name' => "required|string|max:255|unique:exchanges,name,{$exchange->exid},exid",
                'status' => 'required|in:Active,Inactive',
                'currencies' => 'array',
                'currencies.*' => 'exists:currencies,curid',
            ]);

            DB::beginTransaction();

            $exchange->update([
                'name' => $request->name,
                'status' => $request->status,
                'lastUpdatedBy' => Auth::id(),
            ]);

            if ($request->has('currencies')) {
                $exchange->currencies()->sync($request->currencies);
            }

            DB::commit();

            logger()->info('Exchange updated successfully');

            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');

        } catch (\Exception $e) {
            
            DB::rollBack();
            
            logger()->error('Failed to update exchange with exchangeId-'.$exchange->exid, ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            
            return back()->with('error', 'Failed to update exchange: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Exchange $exchange)
    {
        logger()->info('Request to update exchange from '.$exchange->status);
        try {
            
            $exchange->status = ($exchange->status === 'Active') ? 'Inactive' : 'Active';
            $exchange->save();

            if ($exchange->status === 'Inactive') {
                ExchangeWallet::where('exid', $exid)->update(['status' => 'Inactive']);
            }

            logger()->info('Exchange status updated to '.$exchange->status);
            
        } catch (\Exception $e) {

            logger()->error('Failed to update exchange status', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to update status.');
        } 

        return redirect()->route('exchanges.index')->with('success', 'Exchange status updated.');
    }
}
