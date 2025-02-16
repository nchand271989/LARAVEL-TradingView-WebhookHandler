<?php

namespace App\Helpers;

use App\Models\Trade;
use App\Models\WebhookRule;
use Illuminate\Support\Facades\DB;

class OrderRule
{
    public static function R149156309554434048($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $positionType = $action === 'buy' ? 'long' : 'short';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);

            return response()->json([
                'message' => 'Trade processed successfully',
                'close_trade' => $closeTradeResponse,
                'open_trade' => $openTradeResponse
            ], 200);
        });
    }

    public static function R149156309827063808($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $positionType = $action === 'buy' ? 'short' : 'long';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);

            return response()->json([
                'message' => 'Trade processed successfully',
                'close_trade' => $closeTradeResponse,
                'open_trade' => $openTradeResponse
            ], 200);
        });
    }

    public static function R149156310045167616($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $positionType = $action === 'buy' ? 'long' : 'short';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $responseData = $closeTradeResponse->getData(); // Get the data from the response

            if ($responseData->closedTrades == 0) {
                $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);
            } else {
                // Optional: Handle the case where there are already closed trades (if needed)
                $openTradeResponse = null;
            }
            

            return response()->json([
                'message' => 'Trade processed successfully',
                'close_trade' => $closeTradeResponse,
                'open_trade' => $openTradeResponse
            ], 200);
        });
    }

}
