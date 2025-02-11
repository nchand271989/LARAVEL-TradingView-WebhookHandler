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
    public function index(Request $request)
    {
        $userId = Auth::id();

        try {

            $query = Strategy::with('attributes');

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
                Log::info('Applying search filter - '. $request->search);
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $strategies = $query->paginate(10);
            Log::info('Total found strategies - '. $strategies->count(). ' ['.$strategies->pluck('name')->implode(', ').']');
        } catch (\Exception $e) {
            Log::error('Error fetching strategies', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch strategies.');
        } finally {
            DB::disconnect();
        }

        return view('strategies.index', compact('strategies'));
    }

    public function create()
    {
        $userId = Auth::id();
        return view('strategies.create');
    }

    public function store(Request $request)
    {
        Log::info('Requesting to create new strategy', ['user_id' => Auth::id(), 'request' => $request->all()]);
        $userId = Auth::id();

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
                'createdBy' => $userId,
                'status' => $request->status,
                'auto_reverse_order' => $request->has('auto_reverse_order'),
                'lastUpdatedBy' => $userId,
            ]);


            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'stratid' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            Log::info('Strategy Created', ['user_id' => Auth::id()]);

            return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing strategy', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return redirect()->route('strategies.create')->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }
    }

    public function edit(Strategy $strategy)
    {
        Log::info('Requesting to edit strategy -> strategyId-'.$strategy->stratid.', strategyName-'.$strategy->name, ['user_id' => Auth::id(), 'strategy' => $strategy]);
        $userId = Auth::id();

        try {
            $strategy->load('attributes');
        } catch (\Exception $e) {
            Log::error('Error fetching strategy for edit', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to load strategy.');
        } finally {
            DB::disconnect();
        }

        return view('strategies.create', compact('strategy'));
    }

    public function update(Request $request, Strategy $strategy)
    {
        Log::info('Requst for updating strategy with strategyId-'.$strategy->stratid, ['user_id' => Auth::id(), 'strategy' => $strategy, 'request' => $request->all()]);
        $userId = Auth::id();

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
                'lastUpdatedBy' => $userId,
            ]);

        
            StrategyAttribute::where('stratid', $strategy->stratid)->delete();
            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'stratid' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            Log::info('Strategy updated successfully', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating strategy', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }
        return redirect()->route('strategies.index')->with('success', 'Strategy updated successfully.');
    }

    public function toggleStatus(Strategy $strategy)
    {
        $userId = Auth::id();

        try {
            $strategy->update(['status' => $strategy->status === 'Active' ? 'Inactive' : 'Active']);

            Log::info('Strategy status for strategyId '.$strategy->stratid.', '.$strategy->name.' toggled to '.$strategy->status, ['strategy_id' => $strategy->stratid, 'status' => $strategy->status]);

            if ($strategy->status === 'Inactive') {
                // Get the related webhooks
                $webhooks = Webhook::where('stratid', $strategy->stratid)->get();

                // Update each webhook individually
                foreach ($webhooks as $webhook) {
                    $webhook->update(['status' => 'Inactive']);
                }
            }

            return redirect()->route('strategies.index')->with('success', 'Strategy status updated.');
        } catch (\Exception $e) {
            Log::error('Error toggling strategy status', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update strategy status.');
        } finally {
            DB::disconnect();
        }
    }
}
