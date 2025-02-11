<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    public function show(Request $request)
    {
        try {

            $policy = PrivacyPolicy::latest('created_at')->first();

            if (!$policy) {
                return abort(404, 'Policy not found');
            }

            $storedVersion = $request->cookie('policy_version');                              // Older version of Privacy Policy stored in cookies

            $newVersion = $policy->version;                                                   // Latest version of Privacy Policy

            /** Check if Privacy Policy is updated */
            if ($storedVersion !== $newVersion) {
                
                /** Store Privacy Policy version and IDs in cookies */ 
                Cookie::queue('pid', $policy->pid, 60 * 24 * 30);                             // Store for 30 days
                Cookie::queue('policy_version', $newVersion, 60 * 24 * 30);                   // Store for 30 days
            } 

            return view('policy', ['policy' => $policy->content]);

        } catch (\Exception $e) {

            /** Logging */
            logger()->error('Error fetching privacy policy', ['error' => $e->getMessage()]);
            
            return abort(500, 'An error occurred while fetching the privacy policy');
        }
    }
}
