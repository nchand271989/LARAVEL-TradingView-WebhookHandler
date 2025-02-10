<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Webhook;
use App\Models\WebhookAttribute;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $query = Webhook::with('strategy')->orderBy($request->get('sortBy', 'created_at'), $request->get('sortOrder', 'desc'));

        if ($search = $request->get('search')) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $webhooks = $query->paginate(10);
        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        $strategies = Strategy::with('attributes')->get();
        return view('webhooks.create', compact('strategies'));
    }

    public function edit(Webhook $webhook)
    {
        try {
            $webhook->load('attributes');
            $strategies = Strategy::with('attributes')->get();
        } finally {
            DB::disconnect();
        }

        return view('webhooks.create', compact('webhook', 'strategies'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'stratid' => 'required|exists:strategies,stratid',
                'attributes' => 'nullable|array',
            ]);

            DB::beginTransaction();

            $webhook = Webhook::create([
                'webhid' => Str::uuid(),
                'name' => $request->name,
                'stratid' => $request->stratid,
                'createdBy' => auth()->id(),
                'status' => $request->status,
                'lastUpdatedBy' => auth()->id(),
            ]);

            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attribute) {
                    WebhookAttribute::create([
                        'webhid' => $webhook->webhid,
                        'attribute_name' => $attribute['name'],
                        'attribute_value' => $attribute['value'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('webhooks.index')->with('success', 'Webhook created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating webhook: ' . $e->getMessage());
        }
    }


    public function destroy(Webhook $webhook)
    {
        $webhook->delete();
        return back()->with('success', 'Webhook deleted successfully.');
    }

    public function update(Request $request, Webhook $webhook)
    {
        try {
            $request->validate([
                'name' => 'required',
                'stratid' => 'required|exists:strategies,stratid',
                'attributes.*.name' => 'required|string|max:255',
                'attributes.*.value' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $webhook->update([
                'name' => $request->name,
                'stratid' => $request->stratid,
                'status' => $request->status,
                'lastUpdatedBy' => auth()->id(),
            ]);

            // Delete old attributes
            WebhookAttribute::where('webhid', $webhook->webhid)->delete();

            // Insert new attributes
            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attribute) {
                    WebhookAttribute::create([
                        'webhid' => $webhook->webhid,
                        'attribute_name' => $attribute['name'],
                        'attribute_value' => $attribute['value'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('webhooks.index')->with('success', 'Webhook updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating webhook: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Webhook $webhook)
    {
        $webhook->update(['status' => $webhook->status === 'Active' ? 'Inactive' : 'Active']);
        return redirect()->route('webhooks.index');
    }
}
