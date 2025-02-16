<?php

namespace App\Helpers;

use App\Models\Ledger;
use Carbon\Carbon;
use Exception;

class LedgerHelper
{
    public static function createLedgerEntry(string $walletId, float $amount, ?string $transactionType = null, string $description): array
    {
        try {

            if (!$transactionType) {                                                    /** Auto-detect transaction type if not provided */ 
                $transactionType = $amount >= 0 ? 'Credit' : 'Debit';                   /** Match ENUM values */ 
            }
            if (!in_array($transactionType, ['Credit', 'Debit'])) {                     /** Validate transaction type (ensure it's either "Credit" or "Debit") */ 
                throw new Exception("Invalid transaction type: $transactionType");
            }
            $ledger = Ledger::create([
                'wallet_id'         =>  $walletId,
                'amount'            =>  abs($amount),
                'transaction_type'  =>  $transactionType,
                'description'       =>  $description,
                'transaction_time'  =>  Carbon::now(),
            ]);
            return [
                'success'           =>  true,
                'message'           =>  'Ledger entry successful',
                'ledger'            =>  $ledger,
            ];

        } catch (Exception $e) {
            $requestID = generate_snowflake_id();                                       /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Unable to make entry in ledger', [
                        'error'     =>  $e->getMessage()
                    ]
                );
            return [
                'success'           =>  false,
                'message'           =>  'Unable to make entry in ledger',
                'error'             =>  $e->getMessage(),
            ];
        }
    }
}
