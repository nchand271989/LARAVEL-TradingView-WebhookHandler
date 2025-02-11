<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scenario;
use App\Models\ExchangeWallet;
use Illuminate\Support\Facades\Log;
use App\Services\Snowflake;

class ScenarioController extends Controller
{
    public function index()
    {
        $scenarios = Scenario::paginate(10);
        return view('scenarios.index', compact('scenarios'));
    }

    public function create()
    {
        return view('scenarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:scenarios,name',
            'ratio' => 'nullable|numeric|min:0',
            'auto_exit' => 'boolean',
            'stop_loss' => 'boolean',
            'target_profit' => 'boolean',
        ]);

        try {
            $snowflake = new Snowflake(1);
            $scnid = $snowflake->generateId();

            Scenario::create([
                'scnid' => $scnid,
                'name' => $request->name,
                'ratio' => $request->ratio,
                'auto_exit' => $request->auto_exit,
                'stop_loss' => $request->stop_loss,
                'target_profit' => $request->target_profit,
            ]);

            return redirect()->route('scenarios.index')->with('success', 'Scenario created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating scenario: ' . $e->getMessage());
            return back()->withErrors('Error creating scenario.');
        }
    }

    public function assignToWallet(Request $request, $wltid)
    {
        $request->validate([
            'scnid' => 'required|exists:scenarios,scnid',
        ]);

        $existingWallet = ExchangeWallet::where('scnid', $request->scenario_id)->first();

        if ($existingWallet) {
            return back()->withErrors('This scenario is already linked to another wallet.');
        }

        $wallet = ExchangeWallet::findOrFail($wltid);
        $wallet->update(['scnid' => $request->scenario_id]);

        return redirect()->route('wallets.index')->with('success', 'Scenario linked successfully.');
    }
}
