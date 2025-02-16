<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Wallet;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    /** Display a listing of the exchanges.*/
    public function index(Request $request)
    {
        try {

            return fetchFilteredRecords(Exchange::class, $request, ['name', 'status', 'created_at'], 'exchanges.index', ['currencies']);    

        } catch (\Exception $e) {
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error fetching exchanges', [
                        'error'     =>  $e->getMessage(), 
                        'request'   =>  $request->all()
                    ]
                );
            return back()->with('error', 'Failed to fetch strategies.');
        }
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

            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to load create/edit exchange form', [
                        'error'     =>  $e->getMessage(),
                    ]
                );

            return back()->with('error', 'Failed to load create/edit exchange form.');

        }
    }

    /** Create new exchange by storing information in database. */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $exchange = Exchange::create([
                'name'              =>  $request->name,                         /** Exchange name */ 
                'createdBy'         =>  Auth::id(),                             /** Exchange created by user */
                'lastUpdatedBy'     =>  Auth::id(),                             /** Exchange last updated by user */
                'status'            =>  $request->status,                       /** Exchange status i.e Active/Inactive */ 
            ]);
            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);          /** Attach selected currencies with exchange */
            }
            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to create exchange', [
                        'error'     =>  $e->getMessage(), 
                        'request'   =>  $request->all()
                    ]
                );
            return back()->with('error', 'Failed to create exchange: ' . $e->getMessage());
        }

        return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');         
    }

    public function update(Request $request, Exchange $exchange)
    {
        try {
            DB::beginTransaction();
            $exchange->update([
                'name'              =>  $request->name,
                'status'            =>  $request->status,
                'lastUpdatedBy'     =>  Auth::id(),
            ]);
            if ($request->has('currencies')) {
                $exchange->currencies()->sync($request->currencies);
            }
            DB::commit();
            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to update exchange', [
                        'error'     =>  $e->getMessage(), 
                        'request'   =>  $request->all()
                    ]
                );
            return back()->with('error', 'Failed to update exchange: ' . $e->getMessage());
        }
    }
}
