<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Models\TermsAndConditions;
use App\Http\Controllers\Controller; // Import the base Controller class

class TermsController extends Controller
{
    public function show(Request $request)
    {
        try {
            Log::info('Fetching latest terms and conditions');

            $terms = TermsAndConditions::latest('created_at')->first();

            if (!$terms) {
                Log::warning('Terms and conditions not found');
                return abort(404, 'Terms not found');
            }

            $storedVersion = $request->cookie('terms_version');
            $newVersion = $terms->version;

            Log::info('Comparing stored version with latest version', [
                'stored_version' => $storedVersion,
                'new_version' => $newVersion
            ]);

            if ($storedVersion !== $newVersion) {
                Log::info('Updating terms and conditions cookies', [
                    'tid' => $terms->tid,
                    'new_version' => $newVersion
                ]);

                Cookie::queue('tid', $terms->tid, 60 * 24 * 30); // Store for 30 days
                Cookie::queue('terms_version', $newVersion, 60 * 24 * 30); // Store version for 30 days
            }

            Log::info('Displaying terms and conditions');
            return view('terms', ['terms' => $terms->content]);

        } catch (\Exception $e) {
            Log::error('Error fetching terms and conditions', ['error' => $e->getMessage()]);
            return abort(500, 'An error occurred while retrieving terms and conditions');
        }
    }
}
