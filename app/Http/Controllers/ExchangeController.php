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
                Log::info('Applying search filter - '. $request->search);
            }

            if ($request->has('sortBy') && in_array($request->sortBy, ['name', 'status', 'created_at'])) {
                $query->orderBy($request->sortBy, $request->sortOrder == 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $exchanges = $query->paginate(10);
            Log::info('Total found exchnages - '. $exchanges->count(). ' ['.$exchanges->pluck('name')->implode(', ').']');
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchanges - '.$e->getMessage(), ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch exchanges.');
        } finally {
            DB::disconnect();
        }

        return view('exchanges.index', compact('exchanges'));
    }

    public function create()
    {
        return $this->modifyExchange();
    }

    public function edit($exid)
    {
        $exchange = Exchange::where('exid', $exid)->firstOrFail();
        Log::info('Requesting to edit exchange -> exchangeId-'.$exchange->exid.', exchangeName-'.$exchange->name, ['user_id' => Auth::id(), 'exchange' => $exchange]);
        return $this->modifyExchange($exchange);
    }

    private function modifyExchange(Exchange $exchange = null)
    {
        try {
            $currencies = Currency::where('status', 'Active')->get();
            return view('exchanges.create', compact('currencies', 'exchange'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange details in edit -> exchangeId-'.$exchange->exid.', exchangeName-'.$exchange->name, ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to fetch exchange details.');
        } finally {
            DB::disconnect();
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Requesting to create new exchange', ['user_id' => Auth::id(), 'request' => $request->all()]);
            
            DB::beginTransaction();

            $exchange = Exchange::create([
                'name' => $request->name,
                'status' => $request->status,
                'createdBy' => Auth::id(),
                'lastUpdatedBy' => Auth::id(),
            ]);


            if ($request->has('currencies')) {
                $exchange->currencies()->attach($request->currencies);
                Log::info('Currencies attached', ['user_id' => Auth::id(), 'currencies' => $request->currencies]);
            }

            DB::commit();
            Log::info('Exchange Created', ['user_id' => Auth::id()]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create exchange', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }

        return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully.');
    }

    public function update(Request $request, $exid)
    {
        try {
            $exchange = Exchange::where('exid', $exid)->firstOrFail(); // Ensure it finds the correct record
            Log::info('Requst for updating exchange with exchangeId-'.$exchange->exid, ['user_id' => Auth::id(), 'exchange' => $exchange, 'request' => $request->all()]);
            
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
            Log::info('Exchange updated successfully with exchangeId-'.$exchange->exid, ['user_id' => Auth::id()]);

            return redirect()->route('exchanges.index')->with('success', 'Exchange updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update exchange with exchangeId-'.$exchange->exid, ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update exchange: ' . $e->getMessage());
        } finally {
            DB::disconnect();
        }
    }

    public function toggleStatus($exid)
    {
        try {
            $exchange = Exchange::findOrFail($exid);
            
            $exchange->status = ($exchange->status === 'Active') ? 'Inactive' : 'Active';
            $exchange->save();

            Log::info('Exchange status for exchangeId '.$exid.', '.$exchange->name.' toggled to '.$exchange->status, ['exchange_id' => $exchange->exid, 'status' => $exchange->status]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update exchange status', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update status.');
        } finally {
            DB::disconnect();
        }
        return redirect()->route('exchanges.index')->with('success', 'Exchange status updated.');
    }
}
