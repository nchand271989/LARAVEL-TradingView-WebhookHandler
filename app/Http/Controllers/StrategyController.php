<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Strategy;
use App\Models\StrategyAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategyController extends Controller
{
    public function index(Request $request)
    {
        try {
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
        } finally {
            DB::disconnect('mysql');
        }

        return view('strategies.index', compact('strategies'));
    }

    public function create()
    {
        return view('strategies.create');
    }

    public function store(Request $request)
    {
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
                'createdBy' => auth()->id(),
                'status' => $request->status,
                'auto_reverse_order' => $request->has('auto_reverse_order'),
                'lastUpdatedBy' => auth()->id(),
            ]);

            foreach ($request->input('attributes', []) as $attribute) {
                StrategyAttribute::create([
                    'stratid' => $strategy->stratid,
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value'],
                ]);
            }

            DB::commit();
            return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('strategies.create')->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect('mysql');
        }
    }

    public function edit(Strategy $strategy)
    {
        try {
            $strategy->load('attributes');
        } finally {
            DB::disconnect('mysql');
        }

        return view('strategies.create', compact('strategy'));
    }

    public function update(Request $request, Strategy $strategy)
    {
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
                'lastUpdatedBy' => auth()->id(),
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
            return redirect()->route('strategies.index')->with('success', 'Strategy updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        } finally {
            DB::disconnect('mysql');
        }
    }

    public function toggleStatus(Strategy $strategy)
    {
        $strategy->update(['status' => $strategy->status === 'Active' ? 'Inactive' : 'Active']);
        return redirect()->route('strategies.index');
    }
}
