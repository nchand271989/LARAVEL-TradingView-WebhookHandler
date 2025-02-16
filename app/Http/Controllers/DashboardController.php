<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trade;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetching the necessary data
        $query = Trade::join('webhooks', 'trades.webhook_id', '=', 'webhooks.webhid')
            ->join('rules', 'trades.rule_id', '=', 'rules.rid')
            ->join('currencies', 'webhooks.currency_id', '=', 'currencies.curid') // Join currencies table
            ->select(
                'webhooks.webhid', 
                'webhooks.name as webhook_name', 
                'rules.name as rule_name', 
                'currencies.name as currency_name', // Select the currency name
                \DB::raw('count(trades.id) as order_count')
            )
            ->groupBy('webhooks.webhid', 'rules.rid', 'rules.name', 'currencies.name') // Group by webhook_id, rule_id, and currency_name
            ->get();

        // Log the SQL query for debugging

        // Group the result by currency first, then by webhook, then by rule
        $groupedOrders = $query->groupBy('currency_name');

        // Return the grouped data to the view
        return view('dashboard', compact('groupedOrders'));
    }
}
