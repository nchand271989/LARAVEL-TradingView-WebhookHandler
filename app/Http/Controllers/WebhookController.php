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

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('Fetching webhooks', [
                'user_id' => Auth::id(),
                'search' => $request->get('search'),
                'sortBy' => $request->get('sortBy', 'created_at'),
                'sortOrder' => $request->get('sortOrder', 'desc'),
            ]);

            $query = Webhook::with('strategy')->orderBy($request->get('sortBy', 'created_at'), $request->get('sortOrder', 'desc'));

            if ($search = $request->get('search')) {
                $query->where('name', 'LIKE', "%$search%");
            }

            $webhooks = $query->paginate(10);
            Log::info('Webhooks fetched successfully', ['user_id' => Auth::id(), 'count' => $webhooks->count()]);

            return view('webhooks.index', compact('webhooks'));
        } catch (\Exception $e) {
            Log::error('Error fetching webhooks', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error fetching webhooks');
        }
    }

    public function create()
    {
        try {
            Log::info('Fetching strategies for webhook creation', ['user_id' => Auth::id()]);
            $strategies = Strategy::with('attributes')->get();
            return view('webhooks.create', compact('strategies'));
        } catch (\Exception $e) {
            Log::error('Error fetching strategies', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error loading strategies');
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
            Log::error('Error loading webhook for editing', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error loading webhook');
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

            $webhook = Webhook::create([
                'webhid' => Str::uuid(),
                'name' => $request->name,
                'stratid' => $request->stratid,
                'createdBy' => Auth::id(),
                'status' => $request->status,
                'lastUpdatedBy' => Auth::id(),
            ]);

            Log::info('Webhook created successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);

            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attribute) {
                    WebhookAttribute::create([
                        'webhid' => $webhook->webhid,
                        'attribute_name' => $attribute['name'],
                        'attribute_value' => $attribute['value'],
                    ]);
                }
                Log::info('Webhook attributes stored successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);
            }

            DB::commit();
            return redirect()->route('webhooks.index')->with('success', 'Webhook created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating webhook', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Error creating webhook: ' . $e->getMessage());
        }
        finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }

    public function toggleStatus(Webhook $webhook)
    {
        try {
            $newStatus = $webhook->status === 'Active' ? 'Inactive' : 'Active';

            Log::info('Toggling webhook status', [
                'user_id' => Auth::id(),
                'webhid' => $webhook->webhid,
                'previous_status' => $webhook->status,
                'new_status' => $newStatus,
            ]);

            $webhook->update(['status' => $newStatus]);

            Log::info('Webhook status updated successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'new_status' => $newStatus]);
            return redirect()->route('webhooks.index')->with('success', 'Webhook status updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error toggling webhook status', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error toggling webhook status');
        }
        finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
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
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
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

            Log::info('Webhook updated successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);

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
                Log::info('Webhook attributes updated successfully', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid]);
            }

            DB::commit();
            return redirect()->route('webhooks.index')->with('success', 'Webhook updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating webhook', ['user_id' => Auth::id(), 'webhid' => $webhook->webhid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Error updating webhook: ' . $e->getMessage());
        }
        finally {
            DB::disconnect();
            Log::info('Database connection closed', ['user_id' => Auth::id()]);
        }
    }
}
