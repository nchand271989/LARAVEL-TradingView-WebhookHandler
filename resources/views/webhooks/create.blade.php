<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($webhook) ? __('Edit Webhook') : __('Create Webhook') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('webhooks.index') }}">Webhooks</a>
                <span class="text-gray-500">{{ isset($webhook) ? __('Edit Webhook') : __('Create Webhook') }}</span>
            </x-breadcrumb>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form id="webhook-form" method="POST" action="{{ isset($webhook) ? route('webhooks.update', $webhook->webhid) : route('webhooks.store') }}">
                    @csrf
                    @if(isset($webhook))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700">Webhook Name:</label>
                        <input type="text" name="name" value="{{ old('name', $webhook->name ?? '') }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Select Strategy:</label>
                        <select name="stratid" id="strategy-select" class="w-full border rounded p-2" required>
                            <option value="">-- Select Strategy --</option>
                            @foreach ($strategies as $strategy)
                                <option value="{{ $strategy->stratid }}" 
                                    data-attributes='@json($strategy->attributes)'
                                    {{ isset($webhook) && $webhook->stratid == $strategy->stratid ? 'selected' : '' }}>
                                    {{ $strategy->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="strategy-attributes" class="mb-4 {{ isset($webhook) && $webhook->attributes->count() > 0 ? '' : 'hidden' }}">
                        <h3 class="text-gray-700">Strategy Attributes:</h3>
                        <div id="attributes-container">
                            @if(isset($webhook))
                                @foreach($webhook->attributes as $index => $attribute)
                                    <div class="flex justify-between items-center w-full border-b gap-2 pb-2 mb-2">
                                        <input class="text-gray-700 w-1/4 border-0" 
                                               name="attributes[{{ $index }}][name]" 
                                               value="{{ $attribute->attribute_name }}" 
                                               readonly />
                                        <input type="text" 
                                               name="attributes[{{ $index }}][value]" 
                                               class="w-3/4 border rounded p-2" 
                                               value="{{ $attribute->attribute_value }}" 
                                               required />
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                            <option value="Active" {{ (isset($webhook) && $webhook->status == 'Active') ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ (isset($webhook) && $webhook->status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" id="submit-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                        {{ isset($webhook) ? 'Update Webhook' : 'Save Webhook' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const strategySelect = document.getElementById("strategy-select");
            const attributesContainer = document.getElementById("attributes-container");
            const strategyAttributesDiv = document.getElementById("strategy-attributes");
            const form = document.getElementById("webhook-form");
            const submitBtn = document.getElementById("submit-btn");

            function loadAttributes(attributesJson) {
                attributesContainer.innerHTML = "";

                if (!attributesJson || attributesJson === "null") {
                    strategyAttributesDiv.classList.add("hidden");
                    return;
                }

                try {
                    const attributes = JSON.parse(attributesJson);
                    if (attributes.length > 0) {
                        strategyAttributesDiv.classList.remove("hidden");
                        attributes.forEach((attribute, index) => {
                            const attributeDiv = document.createElement("div");
                            attributeDiv.classList.add("flex", "justify-between", "items-center", "w-full", "border-b", "gap-2", "pb-2", "mb-2");

                            attributeDiv.innerHTML = `
                                <input class="text-gray-700 w-1/4 border-0" 
                                       name="attributes[${index}][name]" 
                                       value="${attribute.attribute_name}" 
                                       readonly />
                                <input type="text" 
                                       name="attributes[${index}][value]" 
                                       class="w-3/4 border rounded p-2" 
                                       value="${attribute.attribute_value || ''}" 
                                       required />
                            `;

                            attributesContainer.appendChild(attributeDiv);
                        });
                    } else {
                        strategyAttributesDiv.classList.add("hidden");
                    }
                } catch (error) {
                    console.error("Error parsing attributes JSON:", error);
                }
            }

            strategySelect.addEventListener("change", function () {
                const selectedOption = strategySelect.options[strategySelect.selectedIndex];
                loadAttributes(selectedOption.getAttribute("data-attributes"));
            });

            form.addEventListener("submit", function () {
                submitBtn.disabled = true;
                submitBtn.innerText = "Processing...";
            });

            // Load attributes if editing a webhook
            @if(isset($webhook))
                loadAttributes(`{!! json_encode($webhook->attributes, JSON_HEX_APOS | JSON_HEX_QUOT) !!}`);
            @else
                loadAttributes(@json($webhook->strategy->attributes ?? []));
            @endif
        });
    </script>
</x-app-layout>
