<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WebhookValidationController extends Controller
{
    public function validateUrl($secretkey, $userid, $webhookid, $strategyid, $hash)
    {
        // Fetch the secret key from .env
        $expectedSecretKey = env('HOOK_KEY');
        $hashKey = env('HASH_KEY');

        // Validate secret key
        if ($secretkey !== $expectedSecretKey) {
            return response()->json(['message' => 'Invalid Secret Key'], 403);
        }

        // Create a hash for verification (example using SHA256)
        $generatedHash = hash('sha256', $hashKey . $userid . $webhookid . $strategyid);

        // Compare hashes
        if (!hash_equals($generatedHash, $hash)) {
            return response()->json(['message' => 'Not Authorized'], 403);
        }

        return response()->json([
            'message' => 'URL validated successfully',
            'user_id' => $userid,
            'webhook_id' => $webhookid,
            'strategy_id' => $strategyid
        ]);
    }
}
