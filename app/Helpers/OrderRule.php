<?php

namespace App\Helpers;

use App\Models\Trade;
use App\Models\WebhookRule;
use Illuminate\Support\Facades\DB;

class OrderRule
{
    public static function R100000000000200001($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(
                [
                    'message'   =>  'Invalid data'
                ], 400);
        }

        $positionType = $action === 'buy' ? 'long' : 'short';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);

            return response()->json([
                'message'       =>  'Trade processed successfully',
                'close_trade'   =>  $closeTradeResponse,
                'open_trade'    =>  $openTradeResponse
            ], 200);
        });
    }

    public static function R100000000000200002($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(
                [
                    'message'   =>  'Invalid data'
                ], 400);
        }

        $positionType = $action === 'buy' ? 'short' : 'long';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);

            return response()->json([
                'message'       =>  'Trade processed successfully',
                'close_trade'   =>  $closeTradeResponse,
                'open_trade'    =>  $openTradeResponse
            ], 200);
        });
    }

    public static function R100000000000200003($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $action)
    {
        if (is_null($positionSize) || is_null($price) || !in_array($action, ['buy', 'sell'])) {
            return response()->json(
                [
                    'message'   =>  'Invalid data'
                ], 400);
        }

        $positionType = $action === 'buy' ? 'long' : 'short';

        return DB::transaction(function () use ($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType) {
            $closeTradeResponse = CloseTradeHelper::CloseTrade($webhookid, $strategyid, $ruleId, $walletId, $price);
            $responseData = $closeTradeResponse->getData();

            if ($responseData->closedTrades == 0) {
                $openTradeResponse = OpenTradeHelper::openTrade($webhookid, $strategyid, $ruleId, $walletId, $price, $positionSize, $positionType);
            } else {
                $openTradeResponse = null;
            }
            

            return response()->json([
                'message'       =>  'Trade processed successfully',
                'close_trade'   =>  $closeTradeResponse,
                'open_trade'    =>  $openTradeResponse
            ], 200);
        });
    }

}
