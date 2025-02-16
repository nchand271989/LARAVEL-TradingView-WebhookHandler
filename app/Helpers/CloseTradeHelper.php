<?php

namespace App\Helpers;

use App\Models\Trade;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CloseTradeHelper
{
    public static function closeTrade(string $webhookId, string $strategyId, string $ruleId, string $walletId, float $price)
    {
        try {
            
            $existingTrade = Trade::where([                 /** Fetch the active trade for the given parameters */
                'webhook_id'            =>  $webhookId,
                'strategy_id'           =>  $strategyId,
                'rule_id'               =>  $ruleId,
                'wallet_id'             =>  $walletId,
                'status'                =>  'Active',
            ])->first();

            if (!$existingTrade) {
                return response()->json(['message' => 'No active trade found', 'closedTrades' => 0], 404);
            }

            
            $quantity = $existingTrade->quantity;
            $openingPrice = $existingTrade->openingPrice;

            /** Update trade with closing details */
            $existingTrade->update([
                'closingPrice'          =>  $price,
                'closingTime'           =>  Carbon::now(),
                'status'                =>  'Closed',
            ]);

            /** Calculate Profit & Loss (P&L) */ 
            if ($existingTrade->positionType === 'long') {
                $profitLoss = ($quantity * $price) - ($quantity * $openingPrice);
            } else {
                $profitLoss = ($quantity * $openingPrice) - ($quantity * $price);
            }


            $msg = json_encode(
                [
                    'orderId'           =>  $existingTrade->id, 
                    'message'           =>  ($profitLoss < 0 ? "Trade closed with Loss: " . abs($profitLoss) : "Trade closed with Profit: " . abs($profitLoss))
                ]);


            /** Insert P&L record into Ledger using LedgerHelper */ 
            LedgerHelper::createLedgerEntry(
                $walletId,
                $profitLoss,
                '',
                $msg
            );

            /** Calculate trading fees */ 
            $fees = DeltaExchangeHelper::calculateFee($existingTrade->openingPrice, $price, $existingTrade->quantity);

            /** Insert Trading Fee & Tax as separate entries using LedgerHelper */ 
            $feeEntries = [
                'Trading fee'           =>  $fees['tradingFee'],
                'Trading tax'           =>  $fees['tax'],
            ];
            foreach ($feeEntries as $description => $amount) {
                $msg = json_encode(
                    [
                        'orderId'       =>  $existingTrade->id, 
                        'message'       => "$description deducted: $amount" 
                    ]);
                LedgerHelper::createLedgerEntry($walletId, $amount, 'Debit', $msg);

            }
            
            return response()->json(
                    [
                        'message'       =>  'Trade closed successfully' ,
                        'closedTrades'  =>  1, 
                        'data'          =>  $existingTrade, 
                        'profit_loss'   =>  $profitLoss
                    ]);
        } catch (\Exception $e) {
            $requestID = generate_snowflake_id();           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Trade processing failed while closing', [
                        'error'         =>  $e->getMessage()
                    ]
                );
            return response()->json(['message' => 'Trade processing failed', 'error' => $e->getMessage()], 500);
        }
    }
}
