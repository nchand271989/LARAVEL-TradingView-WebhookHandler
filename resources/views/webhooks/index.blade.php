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
                <div class="flex justify-between mb-4">
                    <form method="GET" class="flex w-full sm:w-auto space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search webhooks..." class="border p-2 rounded">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                    </form>
                    @if (session('is_admin'))
                        <a href="{{ route('webhooks.create') }}" class="hidden sm:block w-[140px] bg-green-500 text-white text-center px-4 py-2 rounded">Add Webhook</a>
                    @endif
                </div>

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Strategy</th>
                            <th class="p-2 text-right">Status</th>
                            @if (session('is_admin'))
                                <th class="hidden sm:block p-2 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webhooks as $webhook)
                            <tr class="border-b">
                                <td class="p-2 text-left">
                                    {{ $webhook->name }}
                                    <br/>
                                    <small class="text-gray-500 cursor-pointer text-blue-500 underline" onclick="copyToClipboard('{{ url('/api/' . env('HOOK_KEY') . '/' . $webhook->createdBy . '/' . $webhook->webhid . '/' . $webhook->stratid . '/' . hash('sha256', env('HASH_KEY') . $webhook->createdBy . $webhook->webhid . $webhook->stratid)) }}')">
                                        Click here to copy URL
                                    </small>
                                </td>
                                <td class="p-2 text-left">{{ $webhook->strategy->name }}</td>
                                <td class="p-2 text-right">
                                    @if (session('is_admin'))
                                        <form method="POST" action="{{ route('webhooks.toggleStatus', $webhook->webhid) }}" class="inline hidden sm:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                                                {{ $webhook->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                                {{ $webhook->status }}
                                            </button>
                                        </form>

                                        <!-- Show only status text on mobile -->
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden 
                                            {{ $webhook->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $webhook->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-white text-sm font-semibold
                                            {{ $webhook->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $webhook->status }}
                                        </span>
                                    @endif
                                </td>
                                @if (session('is_admin'))
                                    <td class="hidden sm:block p-2 space-x-2 text-right">
                                        @if ($webhook->status === 'Inactive')
                                            <a href="{{ route('webhooks.edit', $webhook->webhid) }}" class="text-blue-500 hover:underline">Edit</a>
                                            <button 
                                                onclick="confirmDelete('{{ route('webhooks.destroy', $webhook->webhid) }}')" 
                                                class="bg-red-500 text-white p-1 rounded">
                                                Delete
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $webhooks->links() }}</div>
            </div>
        </div>
    </div>
     <!-- Delete Confirmation Modal -->
     <x-dialog-modal id="delete-confirmation">
        <x-slot name="title">Delete Webhook</x-slot>
        <x-slot name="content">Are you sure you want to delete this webhook?</x-slot>
        <x-slot name="footer">
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Delete</button>
            </form>
        </x-slot>
    </x-dialog-modal>

    <!-- Notification -->
    <div id="copy-notification" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden">
        Webhook URL Copied!
    </div>

    <!-- JavaScript -->
    <script>
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => {
            let notification = document.getElementById('copy-notification');
            notification.classList.remove('hidden');
                                            
            
            // Hide notification after 2 seconds
            setTimeout(() => {
                    notification.classList.add('hidden');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    </script>

    <!-- JavaScript -->
    <script>
        function confirmDelete(actionUrl) {
            document.getElementById("delete-form").action = actionUrl;
            document.getElementById("delete-confirmation").style.display = "block";
        }

        function closeModal() {
            document.getElementById("delete-confirmation").style.display = "none";
        }
    </script>
</x-app-layout>
