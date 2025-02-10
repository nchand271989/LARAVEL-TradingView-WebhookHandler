<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Exchange::with('currencies');

            if ($request->has('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }

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
            DB::disconnect();
        }
    }

    public function create()
    {
        return $this->modifyExchange();
    }

    public function edit(Exchange $exchange)
    {
        return $this->modifyExchange($exchange);
    }

    private function modifyExchange(Exchange $exchange = null)
    {
        try {
            $currencies = Currency::where('status', 'Active')->get();

            return view('exchanges.create', compact('currencies', 'exchange'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch exchange details.');
        } finally {
            DB::disconnect();
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Exchange store method triggered', ['request' => $request->all()]);

            DB::beginTransaction();

            $exchange = Exchange::create([
                'name' => $request->name,
                'status' => $request->status,
                'createdBy' => Auth::id(),
                'lastUpdatedBy' => Auth::id(),
            ]);

            Log::info('Exchange created', ['exchange' => $exchange]);

            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);
                Log::info('Currencies attached to exchange', ['currencies' => $request->currencies]);
            }

            DB::commit();
            Log::info('Transaction committed');

            return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create exchange', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to create exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }
    }

    public function update(Request $request, Exchange $exchange)
    {
        try {
            $request->validate([
                'name' => "required|string|max:255|unique:exchanges,name,{$exchange->exid},exid",
                'status' => 'required|in:Active,Inactive',
                'currencies' => 'array',
                'currencies.*' => 'exists:currencies,curid',
            ]);

            DB::beginTransaction();

            $exchange->update([
                'name' => $request->name,
                'status' => $request->status,
                'lastUpdatedBy' => Auth::id(),
            ]);

            if ($request->has('currencies')) {
                $exchange->currencies()->sync($request->currencies);
            }

            DB::commit();

            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }
    }

    public function toggleStatus(Exchange $exchange)
    {
        try {
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
