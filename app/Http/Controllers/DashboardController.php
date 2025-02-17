<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Define the raw SQL query (without using DB::raw() here)
        $query = "
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    'currency_id', CAST(currency_id AS CHAR),
                    'currencies', currency_name,
                    'webhooks', (
                        SELECT JSON_ARRAYAGG(
                            JSON_OBJECT(
                                'webhook_id', CAST(webhook_id AS CHAR),
                                'webhook', webhook_name,
                                'rules', (
                                    SELECT JSON_ARRAYAGG(
                                        JSON_OBJECT(
                                            'wallet_id', CAST(wallet_id AS CHAR),
                                            'rule_id', CAST(rule_id AS CHAR),
                                            'rule', rule_name,
                                            'trade_count', (
                                                SELECT COUNT(*) 
                                                FROM trades t 
                                                WHERE 
                                                    t.webhook_id = WEB_TAB.webhook_id 
                                                    AND t.currency_id = CUR_TAB.currency_id
                                                    AND t.rule_id = RULE_TAB.rule_id
                                            ),
                                            'p&L', (
                                                SELECT 
                                                    SUM(CASE WHEN transaction_type = 'CREDIT' THEN amount ELSE 0 END) - 
                                                    SUM(CASE WHEN transaction_type = 'DEBIT' THEN amount ELSE 0 END) AS net_amount
                                                FROM 
                                                    ledger l
                                                WHERE
                                                        l.wallet_id = RULE_TAB.wallet_id-- Explicitly refer to outer query's wallet_id
                                                AND
                                                        l.description!='Wallet top-up'
                                                        
                                                ),
                                            'balance', (
                                                SELECT 
                                                    SUM(CASE WHEN transaction_type = 'CREDIT' THEN amount ELSE 0 END) - 
                                                    SUM(CASE WHEN transaction_type = 'DEBIT' THEN amount ELSE 0 END) AS net_amount
                                                FROM ledger l
                                                WHERE l.wallet_id = RULE_TAB.wallet_id
                                            )
                                        )
                                    )
                                    FROM (
                                        SELECT 
                                            DISTINCT r.rid AS rule_id,
                                            t.wallet_id AS wallet_id,
                                            r.name AS rule_name
                                        FROM trades t
                                        JOIN rules r ON t.rule_id = r.rid
                                        WHERE 
                                            t.currency_id = CUR_TAB.currency_id
                                            AND t.webhook_id = WEB_TAB.webhook_id
                                    ) RULE_TAB
                                )
                            )
                        )
                        FROM (
                            SELECT 
                                DISTINCT w.webhid AS webhook_id,
                                w.name AS webhook_name
                            FROM trades t
                            JOIN webhooks w ON t.webhook_id = w.webhid
                            WHERE t.currency_id = CUR_TAB.currency_id
                        ) WEB_TAB
                    )
                )
            ) AS data
            FROM (
                SELECT 
                    DISTINCT c.curid AS currency_id, 
                    c.name AS currency_name
                FROM trades t
                JOIN currencies c ON t.currency_id = c.curid
            ) CUR_TAB;
        ";

        // Execute the raw SQL query and fetch the result
        $result = DB::select($query);  // No need to use DB::raw() here

        // Assuming the query returns an array with a 'data' field
        $groupedOrders = json_decode($result[0]->data, true);

        // Return the data to the view
        return view('dashboard', compact('groupedOrders'));
    }

}

