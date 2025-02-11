<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ExchangeWallet;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\Snowflake;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = \App\Models\ExchangeWallet::select('exchange_wallets.*')
            ->with(['exchange:exid,name']) // Load Exchange Name
            ->withSum('ledger as balance', 'amount') // Calculate Balance
            ->paginate(10);

        return view('wallets.index', compact('wallets'));
    }


    public function create()
    {
        $exchanges = \App\Models\Exchange::where('status', 'Active')->get();
        return view('wallets.create', compact('exchanges'));
    }


    public function store(Request $request)
    {
        \Log::info('Request data:', $request->all()); // Debugging line

        $request->validate([
            'exid' => 'required|integer',
        ]);

        try {
            $snowflake = new Snowflake(1); // Machine ID = 1
            $wltid = $snowflake->generateId();

            $wallet = \App\Models\ExchangeWallet::create([
                'wltid' => $wltid,
                'exid' => $request->exid,
                'status' => 'Active',
            ]);

            return redirect()->route('wallets.index')->with('success', 'Wallet created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating wallet: ' . $e->getMessage());
            return back()->withErrors('Error creating wallet.');
        }
    }

    public function toggleStatus($wltid)
    {
        $userId = Auth::id();

        try {
            // Find the wallet using the wltid
            $wallet = ExchangeWallet::where('wltid', $wltid)->firstOrFail();

            // Check the status of the associated exchange
            if ($wallet->exchange->status === 'Inactive' && $wallet->status === 'Inactive') {
                return back()->with('error', 'Cannot activate wallet because the associated exchange is inactive.');
            }

            // Toggle status
            $wallet->update(['status' => $wallet->status === 'Active' ? 'Inactive' : 'Active']);

            Log::info('Wallet status toggled', ['wallet_id' => $wallet->wltid, 'status' => $wallet->status]);

            return redirect()->route('wallets.index')->with('success', 'Wallet status updated.');
        } catch (\Exception $e) {
            Log::error('Error toggling wallet status', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update wallet status.');
        }
    }


    public function topUp(Request $request, $wltid)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            Ledger::create([
                'wltid' => $wltid,
                'amount' => $request->amount,
                'transaction_type' => 'Credit',
                'description' => 'Wallet top-up',
            ]);

            return redirect()->route('wallets.index')->with('success', 'Wallet topped up successfully.');
        } catch (\Exception $e) {
            Log::error('Error topping up wallet: ' . $e->getMessage());
            return back()->withErrors('Error topping up wallet.');
        }
    }
}

