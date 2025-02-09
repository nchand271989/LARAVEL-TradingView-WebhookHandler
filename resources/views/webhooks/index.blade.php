<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Webhooks</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between mb-4">
                    <form method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border p-2 rounded">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded ml-2">Search</button>
                    </form>
                    <a href="{{ route('webhooks.create') }}" class="bg-green-500 text-white p-2 rounded">Add Webhook</a>
                </div>

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Name</th>
                            <th class="p-2">Strategy</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webhooks as $webhook)
                            <tr class="border-b">
                                <td class="p-2">{{ $webhook->name }}</td>
                                <td class="p-2">{{ $webhook->strategy->name }}</td>
                                <td class="p-2">{{ $webhook->status }}</td>
                                <td class="p-2">
                                    <form method="POST" action="{{ route('webhooks.destroy', $webhook->webhid) }}" onsubmit="return confirm('Delete this webhook?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white p-1 rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $webhooks->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
