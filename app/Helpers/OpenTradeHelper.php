<?php

namespace App\Helpers;

use App\Models\Trade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\FetchTradeInfo;

class OpenTradeHelper
{
    public static function openTrade(string $webhookId, string $strategyId, string $ruleId, string $walletId, float $price, float $positionSize, string $positionType)
    {
        $balance = FetchTradeInfo::fetchBalance($walletId);
        $requiredAmount = ($price * $positionSize) * 1.18; // Includes 18% fee

        if ($balance < $requiredAmount) {
            return response()->json(['message' => 'Insufficient balance', 'openTrades'=>0], 400);
        }

        try {
            // Create a new trade if balance is sufficient
            $newOrder = Trade::create([
                'webhook_id'    => $webhookId,
                'positionType'  => $positionType,
                'strategy_id'   => $strategyId,
                'rule_id'   => $ruleId,
                'wallet_id'     => $walletId,
                'quantity'      => $positionSize,
                'openingPrice'  => $price,
                'openingTime'   => Carbon::now(),
                'status'        => 'Active',
            ]);

            return response()->json(['message' => 'Trade opened successfully', 'openTrades'=> 1 , 'data' => $newOrder], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Trade processing failed', 'error' => $e->getMessage()], 500);
        }
    }
}
