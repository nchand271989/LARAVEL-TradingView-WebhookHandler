<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($strategy) ? 'Edit Strategy' : 'Create Strategy' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('strategies.index') }}">Strategies</a>
                <span class="text-gray-500">{{ isset($strategy) ? 'Edit Strategy' : 'Create Strategy' }}</span>
            </x-breadcrumb>
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Form for Creating or Updating a Strategy -->
                <form method="POST" action="{{ isset($strategy) ? route('strategies.update', $strategy->stratid) : route('strategies.store') }}" id="strategy-form">
                    @csrf
                    @if(isset($strategy))
                        @method('PUT')
                    @endif

                    <!-- Strategy Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Strategy Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $strategy->name ?? '') }}"
                               class="border rounded p-2 w-full">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pine Script -->
                    <div class="mt-4">
                        <label for="pineScript" class="block text-sm font-medium text-gray-700">Pine Script</label>
                        <textarea name="pineScript" id="pineScript" rows="10" class="border rounded p-2 w-full">{{ old('pineScript', $strategy->pineScript ?? '') }}</textarea>
                        @error('pineScript')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attributes Section -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Attributes</label>
                        <div id="attributes-container">
                            @if(isset($strategy) && $strategy->attributes->count() > 0)
                                @foreach($strategy->attributes as $index => $attribute)
                                    <div class="flex gap-2 mb-2 attribute-row">
                                        <input type="text" name="attributes[{{ $index }}][name]" value="{{ $attribute->attribute_name }}" placeholder="Attribute Name" class="border rounded p-2 w-1/2">
                                        <input type="text" name="attributes[{{ $index }}][value]" value="{{ $attribute->attribute_value }}" placeholder="Attribute Value" class="border rounded p-2 w-1/2">
                                        <button type="button" class="rounded remove-attribute"><x-close-icon class="block h-[40px] w-auto" /></button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex gap-2 mb-2 attribute-row">
                                    <input type="text" name="attributes[0][name]" placeholder="Attribute Name" class="border rounded p-2 w-1/2">
                                    <input type="text" name="attributes[0][value]" placeholder="Attribute Value" class="border rounded p-2 w-1/2">
                                    <button type="button" class="rounded remove-attribute"><x-close-icon class="block h-[40px] w-auto" /></button>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-start">
                            <button type="button" id="add-attribute" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">+ Add Attribute</button>
                        </div>
                    </div>

                     <!-- Status -->
                     <div class="mt-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                            <option value="Active" {{ (isset($strategy) && $strategy->status == 'Active') ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ (isset($strategy) && $strategy->status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" id="submit-btn" class="bg-green-500 text-white px-4 py-2 rounded">
                            {{ isset($strategy) ? 'Update Strategy' : 'Create Strategy' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Double Click Prevention & Dynamic Attributes -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let container = document.getElementById("attributes-container");
            let addBtn = document.getElementById("add-attribute");
            let form = document.getElementById("strategy-form");
            let submitBtn = document.getElementById("submit-btn");

            function addAttribute() {
                let index = container.querySelectorAll(".attribute-row").length;
                let row = document.createElement("div");
                row.classList.add("flex", "gap-2", "mb-2", "attribute-row");
                row.innerHTML = `
                    <input type="text" name="attributes[${index}][name]" placeholder="Attribute Name" class="border rounded p-2 w-1/2">
                    <input type="text" name="attributes[${index}][value]" placeholder="Attribute Value" class="border rounded p-2 w-1/2">
                    <button type="button" class="rounded remove-attribute"><x-close-icon class="block h-[40px] w-auto" /></button>
                `;
                container.appendChild(row);
                attachRemoveEvent(row.querySelector(".remove-attribute"));
            }

            function attachRemoveEvent(button) {
                button.addEventListener("click", function () {
                    let row = button.parentElement;
                    if (container.querySelectorAll(".attribute-row").length > 1) {
                        row.remove();
                    }
                });
            }

            // Attach event listeners to existing buttons
            document.querySelectorAll(".remove-attribute").forEach(attachRemoveEvent);
            addBtn.addEventListener("click", addAttribute);

            // Prevent double form submission
            form.addEventListener("submit", function(event) {
                if (submitBtn.disabled) {
                    event.preventDefault(); // Stop form submission if already disabled
                    return;
                }
                submitBtn.disabled = true; // Disable button to prevent double submission
                submitBtn.innerText = "Processing..."; // Optional: Change button text
            });
        });
    </script>

</x-app-layout>
