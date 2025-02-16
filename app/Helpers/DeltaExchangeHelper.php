<?php

namespace App\Helpers;

use Exception;

class DeltaExchangeHelper
{
    public static function calculateFee(float $openingPrice, float $closingPrice, float $quantity): array
    {
        try {
            // Calculate notional value
            $notionalValue = ($openingPrice + $closingPrice) * $quantity;

            // Calculate fees and tax
            $tradingFee = $notionalValue * (0.02 / 100);
            $tax = $tradingFee * (18 / 100);

            return [
                'tradingFee' => round($tradingFee, 8), // Rounded for precision
                'tax' => round($tax, 8),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'Trade processing failed',
                'exception' => $e->getMessage(),
            ];
        }
    }
}
