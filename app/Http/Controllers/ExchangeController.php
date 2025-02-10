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
            Log::info('Fetching exchange list', ['user_id' => Auth::id(), 'request' => $request->all()]);
            
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
            Log::info('Exchanges fetched successfully', ['user_id' => Auth::id(), 'count' => $exchanges->count()]);
            return view('exchanges.index', compact('exchanges'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchanges', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch exchanges.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
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
            Log::info('Fetching exchange details', ['user_id' => Auth::id(), 'exchange' => $exchange]);
            $currencies = Currency::where('status', 'Active')->get();
            return view('exchanges.create', compact('currencies', 'exchange'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange details', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch exchange details.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Storing new exchange', ['user_id' => Auth::id(), 'request' => $request->all()]);
            
            DB::beginTransaction();

            $exchange = Exchange::create([
                'name' => $request->name,
                'status' => $request->status,
                'createdBy' => Auth::id(),
                'lastUpdatedBy' => Auth::id(),
            ]);

            Log::info('Exchange created', ['user_id' => Auth::id(), 'exchange' => $exchange]);

            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);
                Log::info('Currencies attached', ['user_id' => Auth::id(), 'currencies' => $request->currencies]);
            }

            DB::commit();
            Log::info('Transaction committed', ['user_id' => Auth::id()]);

            return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create exchange', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }

    public function update(Request $request, Exchange $exchange)
    {
        try {
            Log::info('Updating exchange', ['user_id' => Auth::id(), 'exchange' => $exchange, 'request' => $request->all()]);
            
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
                Log::info('Currencies updated', ['user_id' => Auth::id(), 'currencies' => $request->currencies]);
            }

            DB::commit();
            Log::info('Exchange updated successfully', ['user_id' => Auth::id()]);

            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update exchange', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }

    public function toggleStatus(Exchange $exchange)
    {
        try {
            Log::info('Toggling exchange status', ['user_id' => Auth::id(), 'exchange' => $exchange]);
            
            $exchange->status = ($exchange->status === 'Active') ? 'Inactive' : 'Active';
            $exchange->save();

            Log::info('Exchange status updated', ['user_id' => Auth::id(), 'exchange' => $exchange]);
            
            return redirect()->route('exchanges.index')->with('success', 'Exchange status updated.');
        } catch (\Exception $e) {
            Log::error('Failed to update exchange status', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update status.');
        } finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }
}
