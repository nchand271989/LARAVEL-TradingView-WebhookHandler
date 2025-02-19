<?php

// app/Http/Controllers/GraphController.php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GraphController extends Controller
{
    public function index($wallet_id = null)
    {
        // If wallet_id is provided, filter by wallet_id; otherwise, fetch all ledger records
        $query = Ledger::query();

        // If wallet_id is provided, filter the ledger entries by wallet_id
        if ($wallet_id) {
            $query->where('wallet_id', $wallet_id);
        }

        // Exclude "Wallet top-up" entries
        $ledgers = $query->where('description', '!=', 'Wallet top-up')
                         ->orderBy('created_at')
                         ->get();


                         if ($ledgers->isEmpty()) {
                            return view('graph.index', ['chartData' => [], 'is24Hours' => false]);
                        }
                
                        // Determine if the data is within 24 hours
                        $firstRecordDate = Carbon::parse($ledgers->first()->date);
                        $lastRecordDate = Carbon::parse($ledgers->last()->date);
                        $isWithin24Hours = $firstRecordDate->diffInHours($lastRecordDate) <= 24;


        $balance = 0;
        $chartData = [];

        // Loop through ledger entries and calculate the balance
        foreach ($ledgers as $ledger) {
            // Convert the date string to a Carbon instance
            $date = Carbon::parse($ledger->created_at)->setTimezone('Asia/Kolkata');;

            // Update balance based on transaction type
            if ($ledger->transaction_type == 'Credit') {
                $balance += $ledger->amount;
            } elseif ($ledger->transaction_type == 'Debit') {
                $balance -= $ledger->amount;
            }

            // Add data to chart data with the appropriate format
            if ($isWithin24Hours) {
                // If data is within 24 hours, show in hours (H:i)
                $chartData[] = [$date->format('H:i'), $balance];
            } else {
                // Otherwise, show in days (Y-m-d)
                $chartData[] = [$date->toDateString(), $balance];
            }
        }

        // Pass the data and the flag indicating if it's within 24 hours to the view
        return view('graph.index', [
            'chartData' => $chartData,
            'is24Hours' => $isWithin24Hours,
            'wallet_id' => $wallet_id,  // Passing wallet_id to the view,
        ]);
    }
}
