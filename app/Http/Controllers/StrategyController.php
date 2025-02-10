<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Strategy;
use App\Models\StrategyAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategyController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        try {
            Log::info('Fetching strategies', ['user_id' => $userId, 'search' => $request->search]);

            $query = Strategy::with('attributes');

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $strategies = $query->paginate(10);
            Log::info('Fetched strategies successfully', ['user_id' => $userId, 'count' => $strategies->count()]);
        } catch (\Exception $e) {
            Log::error('Error fetching strategies', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch strategies.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => $userId]);
        }

        return view('strategies.index', compact('strategies'));
    }

    public function create()
    {
        $userId = Auth::id();
        Log::info('Navigating to create strategy page', ['user_id' => $userId]);
        return view('strategies.create');
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        try {
            Log::info('Storing a new strategy', ['user_id' => $userId, 'request' => $request->all()]);

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

            Log::info('Strategy created successfully', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);

            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'stratid' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            Log::info('Strategy stored successfully', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);

            return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing strategy', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return redirect()->route('strategies.create')->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => $userId]);
        }
    }

    public function edit(Strategy $strategy)
    {
        $userId = Auth::id();

        try {
            Log::info('Fetching strategy for editing', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);
            $strategy->load('attributes');
        } catch (\Exception $e) {
            Log::error('Error fetching strategy for edit', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to load strategy.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => $userId]);
        }

        return view('strategies.create', compact('strategy'));
    }

    public function update(Request $request, Strategy $strategy)
    {
        $userId = Auth::id();

        try {
            Log::info('Updating strategy', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);

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

            Log::info('Strategy updated successfully', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);

            StrategyAttribute::where('stratid', $strategy->stratid)->delete();
            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'stratid' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            Log::info('Strategy update committed', ['user_id' => $userId, 'strategy_id' => $strategy->stratid]);

            return redirect()->route('strategies.index')->with('success', 'Strategy updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating strategy', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => $userId]);
        }
    }

    public function toggleStatus(Strategy $strategy)
    {
        $userId = Auth::id();

        try {
            Log::info('Toggling strategy status', [
                'user_id' => $userId,
                'strategy_id' => $strategy->stratid,
                'current_status' => $strategy->status
            ]);

            $strategy->update(['status' => $strategy->status === 'Active' ? 'Inactive' : 'Active']);

            Log::info('Strategy status updated', [
                'user_id' => $userId,
                'strategy_id' => $strategy->stratid,
                'new_status' => $strategy->status
            ]);

            return redirect()->route('strategies.index')->with('success', 'Strategy status updated.');
        } catch (\Exception $e) {
            Log::error('Error toggling strategy status', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update strategy status.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => $userId]);
        }
    }
}
