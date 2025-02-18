<?php

namespace App\Helpers;

use App\Models\Trade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\FetchTradeInfo;

class OpenTradeHelper
{
    public static function openTrade(string $webhookId, string $strategyId, string $exchangeId, string $currencyId, string $ruleId, string $walletId, string $timeframe, float $price, float $positionSize, string $positionType)
    {
        $balance = FetchTradeInfo::fetchBalance($walletId);

        $leverage = 25;

        // Calculate the required margin (taking leverage into account)
        $positionValue = $price * $positionSize;  // Total position value
        $requiredMargin = $positionValue / $leverage;  // Margin required based on leverage

        // Add applicable tax if needed (adjusting the required margin for taxes, as per your business logic)
        $requiredAmount = ($requiredMargin * (1 + env('APPLICABLE_TAX', 0.18)))*5; 

        if ($balance < $requiredAmount) {
            return response()->json(
                [
                    'message'   => 'Insufficient balance', 'openTrades'=>0
                ], 400);
        }

        try {
            // Create a new trade if balance is sufficient
            $newOrder = Trade::create([
                'webhook_id'    =>  $webhookId,
                'positionType'  =>  $positionType,
                'strategy_id'   =>  $strategyId,
                'exchange_id'   =>  $exchangeId,
                'currency_id'   =>  $currencyId,
                'rule_id'       =>  $ruleId,
                'timeframe'     =>  $timeframe,
                'wallet_id'     =>  $walletId,
                'quantity'      =>  $positionSize,
                'openingPrice'  =>  $price,
                'openingTime'   =>  Carbon::now(),
                'status'        =>  'Active',
            ]);

            return response()->json(
                [
                    'message'   =>  'Trade opened successfully', 
                    'openTrades'=>  1 , 
                    'data'      =>  $newOrder
                ], 201);
        } catch (\Exception $e) {
            $requestID = generate_snowflake_id();           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Trade processing failed while opening new trade', [
                        'error' =>  $e->getMessage()
                    ]
                );
            return response()->json(['message' => 'Trade processing failed', 'error' => $e->getMessage()], 500);
        }
    }
}
