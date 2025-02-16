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
        return fetchFilteredRecords(Wallet::class, $request, ['wltid', 'status'], 'wallets.index', $relations);

    }

    /** Show the form for creating a new wallet. */
    public function create()
    {

        $exchanges = Exchange::where('status', 'Active')->get();
        return view('wallets.create', compact('exchanges'));
    
    }


    public function store(Request $request)
    {
        try {

            $request->validate([
                'exid'                  => 'required|integer',
            ]);
            $wallet = Wallet::create([
                'exchange_id'           => $request->exid,
                'status'                => 'Active',
            ]);

        } catch (\Exception $e) {

            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error creating wallet', [
                        'error'         =>  $e->getMessage(), 
                        'request'       =>  $request->all(), 
                    ]
                );
            return back()->withErrors('Error creating wallet.');

        }

        return redirect()->route('wallets.index')->with('success', 'Wallet created successfully.');
    }


    public function topUp(Request $request, $wltid)
    {
        try {

            $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);
            Ledger::create([
                'wallet_id'             => $wltid,
                'amount'                => $request->amount,
                'transaction_type'      => 'Credit',
                'description'           => 'Wallet top-up',
            ]);

        } catch (\Exception $e) {
            
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error topping up wallet', [
                        'error'         =>  $e->getMessage(), 
                        'request'       =>  $request->all(), 
                    ]
                );
            return back()->withErrors('Error topping up wallet.');
        }

        return redirect()->route('wallets.index')->with('success', 'Wallet topped up successfully.');
    }
}

