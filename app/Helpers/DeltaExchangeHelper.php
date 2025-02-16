<?php

namespace App\Helpers;

use Exception;

class DeltaExchangeHelper
{
    public static function calculateFee(float $openingPrice, float $closingPrice, float $quantity): array
    {
        try {
            
            $notionalValue = ($openingPrice + $closingPrice) * $quantity;                               /** Calculate notional value */ 
            $tradingFee = $notionalValue * (env('DELTA_EXCHANGE_INDIA_FUTURES_MAKER_FEE', 0.0002));     /** Calculate trading fee */ 
            $tax = $tradingFee * (env('APPLICABLE_TAX', 0.18));                                         /** Calculate tax */ 
            return [
                'tradingFee'    =>  round($tradingFee, 8),                                              /** Rounded for precision */ 
                'tax'           =>  round($tax, 8),
            ];
        } catch (Exception $e) {
            $requestID = generate_snowflake_id();                                                       /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error while calculating Fees & Tax', [
                        'error' =>  $e->getMessage()
                    ]
                );
            return [
                'error'         =>  true,
                'message'       =>  'Error while calculating Fees & Tax',
                'exception'     =>  $e->getMessage(),
            ];
        }
    }
}
