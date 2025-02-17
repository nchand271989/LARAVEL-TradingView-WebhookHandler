<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Webhooks</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="text-gray-500">Webhooks</span>
            </x-breadcrumb>
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <x-table-toolbar 
                    searchAction="{{ route('webhooks.index') }}" 
                    searchPlaceholder="Search webhooks..."
                    buttonLabel="Add Webhook"
                    buttonRoute="{{ route('webhooks.create') }}" />

                <x-table 
                    :columns="[
                        'name' => 'Name',
                        'strategy->name' => 'Strategy',
                        'webhook-url' => 'URL',
                        'status' => 'Status',
                    ]"
                    :actions="['Edit']"
                    :items="$records"
                    :detachRelations="[]"
                    editRoute="webhooks.edit"
                    key="webhid" />

                <div class="mt-4">{{ $records->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
