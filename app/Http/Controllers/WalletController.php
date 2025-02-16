<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Exchange;
use App\Models\Wallet;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\Snowflake;

class WalletController extends Controller
{
    /** Display a listing of the wallets.*/
    public function index(Request $request)
    {
        $relations = ['balance'];                                                                                                      
        // $withSum = ['ledger as balance' => 'amount'];                                                               /** Sum the `amount` column from `ledger` relation */ 

        return fetchFilteredRecords(Wallet::class, 
            $request, 
            ['wltid', 'status'], 
            'wallets.index',
            $relations
        );
    }

    /** Show the form for creating a new wallet. */
    public function create()
    {
        $exchanges = Exchange::where('status', 'Active')->get();
        return view('wallets.create', compact('exchanges'));
    }


    public function store(Request $request)
    {
        $requestID = generate_snowflake_id();
        
        logger()->info($requestID.'-> Requested to create new waller', ['request' => $request]);                    /** Logging -> wallet creation request*/

        $request->validate([
            'exid' => 'required|integer',
        ]);

        try {

            $wallet = Wallet::create([
                'exchange_id' => $request->exid,
                'status' => 'Active',
            ]);

            logger()->info($requestID.'-> New wallet Created', ['request' => $request]);                            /** Logging -> wallet created. */

        } catch (\Exception $e) {

            logger()->error($requestID.'-> Error creating wallet: ' . $e->getMessage());

            return back()->withErrors('Error creating wallet.');
        }

        return redirect()->route('wallets.index')->with('success', 'Wallet created successfully.');
    }


    public function topUp(Request $request, $wltid)
    {
        $requestID = generate_snowflake_id();

        logger()->info($requestID.'-> Requested to top up wallet', 
            ['wallet' => $wltid, 'request' => $request]);                                                           /** Logging -> wallet top up request*/

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            Ledger::create([
                'wallet_id' => $wltid,
                'amount' => $request->amount,
                'transaction_type' => 'Credit',
                'description' => 'Wallet top-up',
            ]);

            logger()->info($requestID.'-> Top up successfull on wallet.', 
                ['wallet' => $wltid, 'request' => $request]);                                                       /** Logging -> wallet top up request*/

        } catch (\Exception $e) {
            
            logger()->error($requestID.'-> Error topping up wallet: ' . $e->getMessage());
            
            return back()->withErrors('Error topping up wallet.');
        }

        return redirect()->route('wallets.index')->with('success', 'Wallet topped up successfully.');
    }
}

