<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Webhook;
use App\Models\WebhookAttribute;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Services\Snowflake;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Webhook::with('strategy')->orderBy($request->get('sortBy', 'created_at'), $request->get('sortOrder', 'desc'));

            if ($search = $request->get('search')) {
                $query->where('name', 'LIKE', "%$search%");
                Log::info('Applying search filter - '. $request->search);
            }

            $webhooks = $query->paginate(10);
            Log::info('Total found webhooks - '. $webhooks->count(). ' ['.$webhooks->pluck('name')->implode(', ').']');
        } catch (\Exception $e) {
            Log::error('Error fetching webhooks', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error fetching webhooks');
        } finally {
            DB::disconnect();
        }
        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        try {
            $strategies = Strategy::with('attributes')->get();
            return view('webhooks.create', compact('strategies'));
        } catch (\Exception $e) {
            Log::error('Error fetching strategies', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error loading strategies');
        }finally {
            DB::disconnect();
        }
    }

    public function edit(Webhook $webhook)
    {
        try {
            Log::info('Editing webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);

            $webhook->load('attributes');
            $strategies = Strategy::with('attributes')->get();
            return view('webhooks.create', compact('webhook', 'strategies'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading webhook');
        }finally {
            DB::disconnect();
        }
    }

    public function store(Request $request)
    {
        try {

            Log::info('Creating new webhook', ['user_id' => Auth::id(), 'name' => $request->name, 'stratid' => $request->stratid]);

            $request->validate([
                'name' => 'required',
                'stratid' => 'required|exists:strategies,stratid',
                'attributes' => 'nullable|array',
            ]);

            DB::beginTransaction();

            $snowflake = new Snowflake(1); // Machine ID = 1

            $webhook = Webhook::create([
                'name' => $request->name,
                'stratid' => $request->stratid,
                'createdBy' => Auth::id(),
                'status' => $request->status,
                'lastUpdatedBy' => Auth::id(),
            ]);

            if ($request->has('attributes')) {
                Log::info('test');
                foreach ($request->input('attributes', []) as $attribute) {
                    Log::info('Attribute', ['user_id' => Auth::id(), $attribute]);
                    WebhookAttribute::create([
                        'webhid' => $webhook->webhid,
                        'attribute_name' => $attribute['name'],
                        'attribute_value' => $attribute['value'],
                    ]);
                }
                Log::info('Webhook attributes stored successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating webhook', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error creating webhook: ' . $e->getMessage());
        }
        finally {
            DB::disconnect();
        }
        return redirect()->route('webhooks.index')->with('success', 'Webhook created successfully!');
    }

    public function toggleStatus(Webhook $webhook)
    {
        try {
            $newStatus = $webhook->status === 'Active' ? 'Inactive' : 'Active';

            $webhook->update(['status' => $newStatus]);

            Log::info('Webhook status updated successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'new_status' => $newStatus]);
        } catch (\Exception $e) {
            Log::error('Error toggling webhook status', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error toggling webhook status');
        }
        finally {
            DB::disconnect();
        }
        return redirect()->route('webhooks.index')->with('success', 'Webhook status updated successfully!');
    }

    public function destroy(Webhook $webhook)
    {
        try {
            Log::info('Deleting webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);

            $webhook->delete();
            return back()->with('success', 'Webhook deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error deleting webhook');
        }
        finally {
            DB::disconnect();
        }
    }

    public function update(Request $request, Webhook $webhook)
    {
        try {
            Log::info('Updating webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);

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
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error updating webhook: ' . $e->getMessage());
        }
        finally {
            DB::disconnect();
        }
        return redirect()->route('webhooks.index')->with('success', 'Webhook updated successfully!');
    }
}
