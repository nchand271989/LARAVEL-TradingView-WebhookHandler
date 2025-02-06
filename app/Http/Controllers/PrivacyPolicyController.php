<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\PrivacyPolicy;
use App\Http\Controllers\Controller; // Import the base Controller class

class PrivacyPolicyController extends Controller
{
    public function show(Request $request)
    {
        $policy = PrivacyPolicy::latest('created_at')->first();

        if (!$policy) {
            return abort(404, 'Policy not found');
        }

        $storedVersion = $request->cookie('policy_version');

        // Check if policy is updated
        $newVersion = $policy->version;
        if ($storedVersion !== $newVersion) {
            // Store privacy policy version and IDs in cookies
            Cookie::queue('pid', $privacyPolicy->pid, 60 * 24 * 30); // Store for 30 days
            Cookie::queue('policy_version', $newVersion, 60 * 24 * 30); // Store for 30 days
        }

        return view('policy', ['policy' => $policy->content]);
    }
}
