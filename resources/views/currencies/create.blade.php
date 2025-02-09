<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Currency') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('markets.assets') }}">Markets & Assets</a>
                <a href="{{ route('currencies.index') }}">Currencies</a>
                <span class="text-gray-500">Add Currency</span>
            </x-breadcrumb>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('currencies.store') }}">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="name">
                            Name
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700" for="shortcode">
                            Shortcode
                        </label>
                        <input type="text" name="shortcode" id="shortcode" value="{{ old('shortcode') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('shortcode')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700" for="status">
                            Status
                        </label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Create Currency
                        </button>
                        <a href="{{ route('currencies.index') }}"
                            class="ml-2 text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
