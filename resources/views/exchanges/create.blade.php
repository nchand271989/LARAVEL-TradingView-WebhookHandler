<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($exchange) ? 'Edit Exchange' : 'Create Exchange' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('markets.assets') }}">Markets & Assets</a>
                <a href="{{ route('exchanges.index') }}">Exchanges</a>
                <li class="text-gray-500">Add Exchange</li>
            </x-breadcrumb>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ isset($exchange) ? route('exchanges.update', $exchange->exid) : route('exchanges.store') }}">
                    @csrf
                    @if(isset($exchange))
                        @method('PATCH')
                    @endif

                    <!-- Exchange Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Exchange Name:</label>
                        <input type="text" name="name" value="{{ old('name', $exchange->name ?? '') }}" required class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                        @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <!-- Select Currencies -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Select Currencies:</label>

                        <div class="flex justify-start gap-2 mb-2">
                            <button type="button" id="select-all" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Select All</button>
                            <button type="button" id="deselect-all" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Deselect All</button>
                        </div>

                        <div class="border border-gray-300 rounded-lg p-2 overflow-y-auto" style="max-height: 300px;">
                            @foreach($currencies as $currency)
                                <div class="flex items-center mb-2">
                                    <label class="flex items-center cursor-pointer w-full">
                                        <input type="checkbox" name="currencies[]" value="{{ $currency->curid }}" 
                                            class="currency-checkbox mr-2"
                                            {{ isset($exchange) && $exchange->currencies->contains('curid', $currency->curid) ? 'checked' : '' }}>
                                        <span class="flex items-center space-x-2">
                                            <x-currency>{{ strtoupper($currency->shortcode) }}</x-currency>
                                            <span>{{ $currency->name }}</span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('currencies') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                            <option value="Active" {{ (isset($exchange) && $exchange->status == 'Active') ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ (isset($exchange) && $exchange->status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                            {{ isset($exchange) ? 'Update Exchange' : 'Create Exchange' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            document.querySelectorAll('.currency-checkbox').forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('deselect-all').addEventListener('click', function() {
            document.querySelectorAll('.currency-checkbox').forEach(checkbox => checkbox.checked = false);
        });
    </script>
</x-app-layout>
