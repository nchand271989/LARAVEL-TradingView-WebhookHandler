<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exchange Wallets</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="text-gray-500">Wallets</span>
            </x-breadcrumb>
            
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <x-table-toolbar 
                    searchAction="{{ route('wallets.index') }}" 
                    searchPlaceholder="Search Wallets..."
                    buttonLabel="Add Wallet"
                    buttonRoute="{{ route('wallets.create') }}" />

                <x-table 
                    :columns="[
                        'wltid' => 'Wallet ID',
                        'exchange->name' => 'Exchange Name',
                        'balance' => 'Balance',
                        'status' => 'Status',
                        'topup' => 'Top Up',
                    ]"
                    :actions="['']"
                    :items="$records"
                    editRoute=""
                    deleteRoute=""
                    topUpRoute="wallets.topup"
                    topUpWalletKey="wltid" />

                <div class="mt-4">{{ $records->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
