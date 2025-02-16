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
            // Auto-detect transaction type if not provided
            if (!$transactionType) {
                $transactionType = $amount >= 0 ? 'Credit' : 'Debit'; // Match ENUM values
            }

            // Validate transaction type (ensure it's either "Credit" or "Debit")
            if (!in_array($transactionType, ['Credit', 'Debit'])) {
                throw new Exception("Invalid transaction type: $transactionType");
            }

            // Create ledger entry
            $ledger = Ledger::create([
                'wallet_id'        => $walletId,
                'amount'           => abs($amount), // Store as positive value
                'transaction_type' => $transactionType, // Ensure it matches ENUM
                'description'      => $description,
                'transaction_time' => Carbon::now(),
            ]);

            return [
                'success' => true,
                'message' => 'Ledger entry successful',
                'ledger'  => $ledger,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Unable to insert ledger entry',
                'error'   => $e->getMessage(),
            ];
        }
    }
}
