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
            $requestID = generate_snowflake_id();                           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Error fetching strategies', [
                        'error'         =>  $e->getMessage(), 
                        'request'       =>  $request
                    ]
                );
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

            $requestID = generate_snowflake_id();                           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to load attributes for strategy while editing', [
                        'error'         =>  $e->getMessage(), 
                    ]
                );
            return back()->with('error', 'Failed to load strategy.');
        }

        return view('strategies.create', compact('strategy'));
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $request->validate([
                'name'                  =>  'required|string|max:255',
                'pineScript'            =>  'required|string',
                'attributes.*.name'     =>  'required|string|max:255',
                'attributes.*.value'    =>  'required|string|max:255',
            ]);
            $strategy = Strategy::create([
                'name'                  =>  $request->name,
                'pineScript'            =>  $request->pineScript,
                'createdBy'             =>  Auth::id(),
                'lastUpdatedBy'         =>  Auth::id(),
                'status'                =>  $request->status,
            ]);


            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'strategy_id'       =>  $strategy->stratid,
                    'attribute_name'    =>  $attribute['name'],
                    'attribute_value'   =>  $attribute['value'],
                ]);
            }
            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            $requestID = generate_snowflake_id();                           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to create strategy', [
                        'error'         =>  $e->getMessage(), 
                        'request'       =>  $request->all(), 
                    ]
                );
            return redirect()->route('strategies.create')->with('error', 'Error: ' . $e->getMessage());
        } 

        return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
    }

    public function update(Request $request, Strategy $strategy)
    {
        try {

            DB::beginTransaction();
            $request->validate([
                'name'                  =>  'required|string|max:255',
                'pineScript'            =>  'required|string',
                'attributes.*.name'     =>  'required|string|max:255',
                'attributes.*.value'    =>  'required|string|max:255',
            ]);
            $strategy->update([
                'name'                  =>  $request->name,
                'pineScript'            =>  $request->pineScript,
                'status'                =>  $request->status,
                'lastUpdatedBy'         =>  Auth::id(),
            ]);
            StrategyAttribute::where('strategy_id', $strategy->stratid)->delete();
            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'strategy_id'       =>  $strategy->stratid,
                    'attribute_name'    =>  $attribute['name'],
                    'attribute_value'   =>  $attribute['value'],
                ]);
            }
            DB::commit();

        } catch (\Exception $e) {
            
            DB::rollBack();
            $requestID = generate_snowflake_id();                           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to update strategy', [
                        'error'         =>  $e->getMessage(), 
                        'request'       =>  $request->all(), 
                    ]
                );
            return back()->with('error', 'Failed to update strategy: ' . $e->getMessage());

        } 

        return redirect()->route('strategies.index')->with('success', 'Strategy updated successfully.');
    }

}
