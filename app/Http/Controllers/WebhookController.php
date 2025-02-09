<?php

namespace App\Http\Controllers;

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
                'status' => 'Active',
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
}
