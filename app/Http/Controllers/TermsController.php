<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Models\TermsAndConditions;

class TermsController extends Controller
{
    public function show(Request $request)
    {
        try {

            $terms = TermsAndConditions::latest('created_at')->first();

            if (!$terms) {
                return abort(404, 'Terms not found');
            }

            $storedVersion = $request->cookie('terms_version');                              // Older version of Terms & Conditions stored in cookies
            
            $newVersion = $terms->version;                                                   // Latest version of Terms & Conditions


            /** Check if Terms & Condition is updated */
            if ($storedVersion !== $newVersion) {

                /** Store Terms & Conditions version and IDs in cookies */ 
                Cookie::queue('tid', $terms->tid, 60 * 24 * 30);                             // Store for 30 days
                Cookie::queue('terms_version', $newVersion, 60 * 24 * 30);                   // Store version for 30 days
            }

            return view('terms', ['terms' => $terms->content]);

        } catch (\Exception $e) {

            /** Logging */
            logger()->error('Error fetching terms and conditions', ['error' => $e->getMessage()]);
            
            return abort(500, 'An error occurred while retrieving terms and conditions');
        }
    }
}
