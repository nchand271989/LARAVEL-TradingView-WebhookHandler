<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\TermsAndConditions;
use App\Http\Controllers\Controller; // Import the base Controller class

class TermsController extends Controller
{
    public function show(Request $request)
    {
        $terms = TermsAndConditions::latest('created_at')->first();

        if (!$terms) {
            return abort(404, 'Terms not found');
        }

        $storedVersion = $request->cookie('terms_version');

        // Check if terms are updated
        $newVersion = $terms->version;
        if ($storedVersion !== $newVersion) {
            // Store terms and condition version and IDs in cookies
            Cookie::queue('tid', $terms->tid, 60 * 24 * 30); // Store for 30 days
            Cookie::queue('terms_version', $newVersion, 60 * 24 * 30); // Store version for 30 days
        }

        return view('terms', ['terms' => $terms->content]);
    }
}
