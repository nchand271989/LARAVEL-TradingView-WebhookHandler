<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Define the raw SQL query to fetch the result from the stored function
        $query = "
            SELECT GetCurrencyData() AS data;
        ";
    
        // Execute the raw SQL query and fetch the result
        $result = DB::select($query);
    
        // Check if the result is not empty and decode the JSON
        if (isset($result[0]->data)) {
            // Decode the JSON string (if needed) to convert it into a PHP array
            $groupedOrders = json_decode($result[0]->data, true);
    
            // Ensure the result is a valid array (in case the JSON is malformed)
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("JSON decode error: " . json_last_error_msg());
                $groupedOrders = []; // Provide a fallback in case of a decoding issue
            }
    
            // Now, process the 'webhooks' and 'rules' fields
            foreach ($groupedOrders as &$currencyData) {
                // Decode the webhooks field if it's a stringified JSON array
                if (isset($currencyData['webhooks']) && is_string($currencyData['webhooks'])) {
                    $currencyData['webhooks'] = json_decode($currencyData['webhooks'], true);
                    
                    // Decode the 'rules' field for each webhook if it's a stringified JSON array
                    foreach ($currencyData['webhooks'] as &$webhook) {
                        if (isset($webhook['rules']) && is_string($webhook['rules'])) {
                            $webhook['rules'] = json_decode($webhook['rules'], true);
                        }
                    }
                }
            }
        } else {
            // In case no data was returned, log the error and provide fallback data
            Log::error("No data returned from GetCurrencyData function.");
            $groupedOrders = [];
        }
    
        // Log the processed $groupedOrders to see its structure
        Log::info($groupedOrders);
    
        // Return the data to the view
        return view('dashboard', compact('groupedOrders'));
    }
    
}
