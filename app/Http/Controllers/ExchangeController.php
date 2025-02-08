<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Exchange::with('currencies');

            // Search feature
            if ($request->has('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            // Sorting feature
            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $exchanges = $query->paginate(10);
            return view('exchanges.index', compact('exchanges'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch exchanges.');
        } finally {
            DB::disconnect(); // Close DB connection
        }
    }

    public function create(Request $request)
    {
        try {
            $currencies = Currency::where('status', 'Active')->get();
            $exchange = null;

            if ($request->has('id')) {
                $exchange = Exchange::with('currencies')->find($request->id);
            }

            return view('exchanges.create', compact('currencies', 'exchange'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch currencies.');
        } finally {
            DB::disconnect();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:exchanges,name',
                'status' => 'required|in:Active,Inactive',
                'currencies' => 'array',
            ]);

            DB::beginTransaction();

            $exchange = Exchange::create([
                'name' => $request->name,
                'status' => $request->status,
                'createdBy' => Auth::id(),
            ]);

            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);
            }

            DB::commit();

            return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create exchange.');
        } finally {
            DB::disconnect(); // Close DB connection
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => "required|string|max:255|unique:exchanges,name,$id,exid",
                'status' => 'required|in:Active,Inactive',
                'currencies' => 'array',
            ]);

            DB::beginTransaction();

            $exchange = Exchange::findOrFail($id);
            $exchange->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            if ($request->has('currencies')) {
                $exchange->currencies()->sync($request->currencies);
            }

            DB::commit();

            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update exchange.');
        } finally {
            DB::disconnect();
        }
    }

    public function toggleStatus($exchangeId)
    {
        try {
            $exchange = Exchange::findOrFail($exchangeId);
            $exchange->status = ($exchange->status === 'Active') ? 'Inactive' : 'Active';
            $exchange->save();

            return redirect()->route('exchanges.index')->with('success', 'Exchange status updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update status.');
        } finally {
            DB::disconnect();
        }
    }
}
