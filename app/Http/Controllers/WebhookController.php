<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Webhook;
use App\Models\WebhookAttribute;
use App\Models\Strategy;
use App\Models\Exchange;
use App\Models\Scenario;
use App\Models\Rule;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        try {
            return fetchFilteredRecords(Webhook::class, $request, ['name', 'status', 'created_at'], 'webhooks.index', ['strategy']);    

        } catch (\Exception $e) {

            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error fetching webhooks', [
                        'error'             =>  $e->getMessage(), 
                        'request'           =>  $request->all(), 
                    ]
                );
            return back()->with('error', 'Error fetching webhooks');

        } 

        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        try {
            
            $strategies = Strategy::with('attributes')->get();
            $exchanges = Exchange::where('status', 'Active')
                        ->with('currencies')
                        ->get();

            $rules  = Rule::all(); // Load all available scenarios
            
            return view('webhooks.create', compact('strategies', 'exchanges', 'rules'));

        } catch (\Exception $e) {
            
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error fetching strategies, exchanges or rules while creating new webhook', [
                        'error'             =>  $e->getMessage(), 
                    ]
                );
            return back()->with('error', 'Error fetching strategies, exchanges or rules while creating new webhook');

        }
    }

    public function edit(Webhook $webhook)
    {
        try {   
            
            $webhook->load('attributes');
            $strategies = Strategy::with('attributes')->get();
            $exchanges = Exchange::where('status', 'Active')
                        ->with('currencies')
                        ->get();
            $rules  = Rule::all();
            return view('webhooks.create', compact('webhook', 'exchanges', 'strategies', 'rules'));
            
        } catch (\Exception $e) {

            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error fetching strategies, exchanges or rules while updating new webhook', [
                        'error'             =>  $e->getMessage(), 
                    ]
                );
            return back()->with('error', 'Error fetching strategies, exchanges or rules while updating new webhook');

        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $request->validate([
                'name'                      =>  'required',
                'stratid'                   =>  'required|exists:strategies,stratid',
                'attributes'                =>  'nullable|array',
            ]);
            $webhook = Webhook::create([
                'name'                      =>  $request->name,
                'strategy_id'               =>  $request->stratid,
                'exchange_id'               =>  $request->exid,
                'currency_id'               =>  $request->curid,
                'createdBy'                 =>  Auth::id(),
                'status'                    =>  $request->status,
                'lastUpdatedBy'             =>  Auth::id(),
            ]);
            if ($request->has('rules')) {
                $rules = collect($request->rules)->map(fn($id) => (int) $id);   /** Convert all rule IDs to integers */ 
                $webhookId = (int) $webhook->webhid;                            /** Ensure webhook_id is an integer */ 
                $webhook->rules()->attach($rules);
                foreach ($rules as $ruleId) {
                    Wallet::create([
                        'wallet_id'         =>  generate_snowflake_id(),        /** Generate unique ID */ 
                        'webhook_id'        =>  $webhookId,
                        'rule_id'           =>  $ruleId,
                        'status'            =>  'Active',
                    ]);
                }
            }
            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attribute) {
                    Log::info('Attribute', ['user_id' => Auth::id(), $attribute]);
                    WebhookAttribute::create([
                        'webhook_id'        =>  $webhook->webhid,
                        'attribute_name'    =>  $attribute['name'],
                        'attribute_value'   =>  $attribute['value'],
                    ]);
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            
            DB::rollBack();
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error creating webhook', [
                        'error'             =>  $e->getMessage(), 
                        'request'           =>  $request->all(),
                    ]
                );
            return back()->with('error', 'Error creating webhook: ' . $e->getMessage());
        }

        return redirect()->route('webhooks.index')->with('success', 'Webhook created successfully!');
    }


    public function update(Request $request, Webhook $webhook)
    {
        try {

            DB::beginTransaction();
            $requestData = json_encode($request->all(), JSON_PRETTY_PRINT);

            $request->validate([
                'name'                      =>  'required',
                'stratid'                   =>  'required|exists:strategies,stratid',
                'attributes.*.name'         =>  'required|string|max:255',
                'attributes.*.value'        =>  'required|string|max:255',
            ]);
            $webhook->update([
                'name'                      =>  $request->name,
                'strategy_id'               =>  $request->stratid,
                'exchange_id'               =>  $request->exid,
                'currency_id'               =>  $request->curid,
                'status'                    =>  $request->status,
                'lastUpdatedBy'             =>  auth()->id(),
            ]);
            WebhookAttribute::where('webhook_id', $webhook->webhid)->delete();
            if ($request->has('rules')) {
                $rules = collect($request->rules)->map(fn($id) => (int) $id);   /** Convert all rule IDs to integers */ 
                $webhookId = (int) $webhook->webhid;                            /** Ensure webhook_id is an integer */ 
                $webhook->rules()->sync($rules);
                foreach ($rules as $ruleId) {                                   /** Check for wallets related to each rule and create new ones if needed */ 
                    $existingWallet = Wallet::where('webhook_id', $webhookId)   /** Check if a wallet already exists for this webhook_id and rule_id */ 
                                            ->where('rule_id', $ruleId)
                                            ->first();
                    if (!$existingWallet) {
                        Wallet::create([                                        /** If no wallet exists for the rule_id, create a new one */ 
                            'wallet_id'     =>  generate_snowflake_id(),             
                            'webhook_id'    =>  $webhookId,
                            'rule_id'       =>  $ruleId,
                            'status'        =>  'Active',
                        ]);
                    }
                }
            }
            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attribute) {
                    WebhookAttribute::create([
                        'webhook_id'        =>  $webhook->webhid,
                        'attribute_name'    =>  $attribute['name'],
                        'attribute_value'   =>  $attribute['value'],
                    ]);
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            
            DB::rollBack();
            $requestID = generate_snowflake_id();                               /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error updating webhook', [
                        'error'             =>  $e->getMessage(), 
                        'request'           =>  $request->all(),
                    ]
                );
            return back()->with('error', 'Error updating webhook: ' . $e->getMessage());
        }
        
        return redirect()->route('webhooks.index')->with('success', 'Webhook updated successfully!');
    }
}
