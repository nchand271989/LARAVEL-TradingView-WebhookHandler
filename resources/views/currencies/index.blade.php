<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Currencies') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('markets.assets') }}">Markets & Assets</a>
                <span class="text-gray-500">Currencies</span>
            </x-breadcrumb>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <x-table-toolbar 
                    searchAction="{{ route('currencies.index') }}" 
                    searchPlaceholder="Search currencies..."
                    buttonLabel="Add Currency"
                    buttonRoute="{{ route('currencies.create') }}" />

                <x-table 
                    :columns="[
                        'name' => 'Name',
                        'shortcode' => 'Code',
                        'status' => 'Status',
                    ]"
                    :items="$records"
                    statusToggleRoute="currencies.toggleStatus"
                    :detachRelations="['exchanges']" />

                <div class="mt-4">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
