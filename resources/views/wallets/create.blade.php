<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Exchange Wallet</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('wallets.index') }}">Wallets</a>
                <span class="text-gray-500">Create Wallet</span>
            </x-breadcrumb>

            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('wallets.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Select Exchange</label>
                        <select name="exid" required class="border p-2 w-full rounded">
                            <option value="" disabled selected>Select an exchange</option>
                            @foreach ($exchanges as $exchange)
                                <option value="{{ $exchange->exid }}">{{ $exchange->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Create Wallet</button>
                        <a href="{{ route('wallets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
