<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\FetchTradeInfo;
use App\Helpers\CloseTradeHelper;
use App\Helpers\OpenTradeHelper;
use App\Helpers\OrderRule;
use App\Models\Webhook;

class WebhookValidationController extends Controller
{
    public function validateUrl(Request $request, $secretkey, $userid, $webhookid, $strategyid, $exchangeid, $currencyid, $hash)
    {

        $expectedSecretKey = env('HOOK_KEY');
        $hashKey = env('HASH_KEY');
        if ($secretkey !== $expectedSecretKey) {                                                /** Validate Secret Key */
            logger()
                ->info(
                    'Invalid Secret Key', [
                        'request'   =>  $request()
                    ]
                );
            return response()->json(['message' => 'Invalid Secret Key'], 403);
            
        }

        $generatedHash = hash('sha256', $hashKey . $userid . $webhookid . $strategyid);
        if (!hash_equals($generatedHash, $hash)) {                                              /** Validate Hash Key */
            logger()
                ->info(
                    'Not Authorized', [
                        'request'   =>  $request()
                    ]
                );
            return response()->json(['message' => 'Not Authorized'], 403);
        }

        if (Webhook::where('webhid', $webhookid)->where('status', 'Inactive')->exists()) {      /** Validate webhook is active */
            logger()
                ->info(
                    'Invalid Request', [
                        'request'   =>  $request()
                    ]
                );
            return response()->json(['message' => 'Invalid request'], 400);
            
        }

        $data = $request->only(['Position Size', 'Action', 'Price', 'Timeframe']);
        $positionSize = $data['Position Size'] ?? null;
        $action = strtolower($data['Action'] ?? '');
        $timeframe = $data['Timeframe'] ?? '5m';
        if (is_numeric($timeframe)) {
            $timeframe .= 'm';
        }

        $price = $data['Price'] ?? null;
        $ruleIds = FetchTradeInfo::fetchRulesId($webhookid);                                    /** Fetch scenario and wallet IDs */
        $webhookRules = [];
        
        foreach ($ruleIds as $ruleId) {
            $walletId = FetchTradeInfo::fetchWalletId($ruleId, $webhookid);
            $webhookRules[] = OrderRule::{"R" . $ruleId}($webhookid, $strategyid, $exchangeid, $currencyid, $ruleId, $walletId, $timeframe, $price, $positionSize, $action); 
        }

        return response()->json(['rules' => $ruleIds, 'webhookRules' => $webhookRules], 200);
    }
}
