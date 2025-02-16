<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Exchanges') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('markets.assets') }}">Markets & Assets</a>
                <span class="text-gray-500">Exchanges</span>
            </x-breadcrumb>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <x-table-toolbar 
                    searchAction="{{ route('exchanges.index') }}" 
                    searchPlaceholder="Search exchanges..."
                    buttonLabel="Add Exchange"
                    buttonRoute="{{ route('exchanges.create') }}" />
                <x-table 
                    :columns="[
                        'name' => 'Name',
                        'currencies' => 'Currencies',
                        'status' => 'Status',
                    ]"
                    :actions="['Edit']"
                    :items="$records"
                    :detachRelations="['wallets']"
                    editRoute="exchanges.edit"
                    deleteRoute="exchanges.destroy"
                    key="exid" />

                <div class="mt-4">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
