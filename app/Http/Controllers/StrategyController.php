<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Strategy;
use App\Models\Webhook;
use App\Models\StrategyAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategyController extends Controller
{
    /** Display a listing of the strategies.*/
    public function index(Request $request)
    {
        try {

            return fetchFilteredRecords(Strategy::class, $request, ['name', 'status', 'created_at'], 'strategies.index', ['attributes']);    

        } catch (\Exception $e) {
            
            logger()->error('Error fetching strategies', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to fetch strategies.');

        }
    }

    /** Show the form for creating a new strategy. */
    public function create()
    {
        return view('strategies.create');
    }

    /** Show the form for editing of strategy selected. */
    public function edit(Strategy $strategy)
    {  
        try {

            $strategy->load('attributes');

        } catch (\Exception $e) {
            
            logger()->error('Failed to load attributes for strategy.', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Failed to load strategy.');
        }

        return view('strategies.create', compact('strategy'));
    }

    public function store(Request $request)
    {
        $requestID = generate_snowflake_id();                                                                   /** Unique log id to indetify request flow */
        
        logger()->info($requestID.'-> Requested to create new strategy', ['request' => $request]);              /** Logging -> strategy creation request*/

        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'pineScript' => 'required|string',
                'attributes.*.name' => 'required|string|max:255',
                'attributes.*.value' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $strategy = Strategy::create([
                'name' => $request->name,
                'pineScript' => $request->pineScript,
                'auto_reverse_order' => $request->has('auto_reverse_order'),
                'createdBy' => Auth::id(),
                'lastUpdatedBy' => Auth::id(),
                'status' => $request->status,
            ]);


            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'strategy_id' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            
            logger()->info($requestID.'-> New strategy Created', ['request' => $request]);                      /** Logging -> strategy created. */

        } catch (\Exception $e) {

            DB::rollBack();
            
            logger()->error($requestID.'-> Failed to create strategy', ['error' => $e->getMessage()]);          /** Logging -> strategy creation error */
            
            return redirect()->route('strategies.create')->with('error', 'Error: ' . $e->getMessage());
        } 

        return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
    }

    public function update(Request $request, Strategy $strategy)
    {
        $requestID = generate_snowflake_id();      

        logger()->info($requestID.'-> Request for updating strategy', ['request' => $request->all()]);

        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'pineScript' => 'required|string',
                'attributes.*.name' => 'required|string|max:255',
                'attributes.*.value' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $strategy->update([
                'name' => $request->name,
                'pineScript' => $request->pineScript,
                'status' => $request->status,
                'auto_reverse_order' => $request->has('auto_reverse_order'),
                'lastUpdatedBy' => Auth::id(),
            ]);

        
            StrategyAttribute::where('strategy_id', $strategy->stratid)->delete();
            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'strategy_id' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            
            logger()->info($requestID.'-> Strategy updated successfully', ['request' => $request->all()]);

        } catch (\Exception $e) {
            
            DB::rollBack();
            
            logger()->error($requestID.'-> Error updating strategy', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to update strategy: ' . $e->getMessage());

        } 

        return redirect()->route('strategies.index')->with('success', 'Strategy updated successfully.');
    }

}
