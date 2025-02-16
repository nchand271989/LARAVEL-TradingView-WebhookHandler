<?php

namespace App\Helpers;

use App\Models\Trade;
use App\Models\WebhookRule;
use Illuminate\Support\Facades\DB;

class FetchTradeInfo
{
    public static function fetchRulesId($webhookId)
    {
        return WebhookRule::where('webhook_id', $webhookId)
                ->pluck('rule_id');
    }


    public static function fetchWalletId($ruleId, $webhookId)
    {
        return DB::table('wallets')
            ->where('rule_id', $ruleId)
            ->where('webhook_id', $webhookId)
            ->value('wltid');
    }

    public static function fetchBalance($walletId)
    {
        return DB::table('ledger')
            ->where('wallet_id', $walletId)
            ->selectRaw("SUM(CASE WHEN transaction_type = 'Credit' THEN amount ELSE -amount END) as balance")
            ->value('balance') ?? 0;
    }


}
