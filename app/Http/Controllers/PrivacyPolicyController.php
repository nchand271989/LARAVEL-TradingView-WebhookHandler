<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Models\PrivacyPolicy;
use App\Http\Controllers\Controller; // Import the base Controller class

class PrivacyPolicyController extends Controller
{
    public function show(Request $request)
    {
        try {
            Log::info('Fetching latest privacy policy');

            $policy = PrivacyPolicy::latest('created_at')->first();

            if (!$policy) {
                Log::warning('Privacy policy not found');
                return abort(404, 'Policy not found');
            }

            $storedVersion = $request->cookie('policy_version');
            Log::info('Stored policy version from cookie', ['storedVersion' => $storedVersion]);

            // Check if policy is updated
            $newVersion = $policy->version;
            if ($storedVersion !== $newVersion) {
                Log::info('New privacy policy version detected', ['newVersion' => $newVersion]);

                // Store privacy policy version and IDs in cookies
                Cookie::queue('pid', $policy->pid, 60 * 24 * 30); // Store for 30 days
                Cookie::queue('policy_version', $newVersion, 60 * 24 * 30); // Store for 30 days

                Log::info('Updated cookies with new policy details', [
                    'pid' => $policy->pid,
                    'policy_version' => $newVersion,
                ]);
            } else {
                Log::info('Privacy policy version unchanged');
            }

            return view('policy', ['policy' => $policy->content]);
        } catch (\Exception $e) {
            Log::error('Error fetching privacy policy', ['error' => $e->getMessage()]);
            return abort(500, 'An error occurred while fetching the privacy policy');
        }
    }
}
