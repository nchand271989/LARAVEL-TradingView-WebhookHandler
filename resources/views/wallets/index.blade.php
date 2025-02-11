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
                <div class="flex justify-between mb-4">
                    <form method="GET" class="flex w-full sm:w-auto space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search wallets..." class="border p-2 rounded">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                    </form>
                    @if (session('is_admin'))
                        <a href="{{ route('wallets.create') }}" class="hidden sm:block w-[140px] bg-green-500 text-white text-center px-4 py-2 rounded">Create Wallet</a>
                    @endif
                </div>

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Wallet ID</th>
                            <th class="p-2 text-left">Exchange</th>
                            <th class="p-2 text-left">Balance</th>
                            <th class="p-2 text-left">Status</th>
                            @if (session('is_admin'))
                                <th class="hidden sm:block p-2 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $wallet)
                            <tr class="border-b">
                                <td class="p-2 text-left">{{ $wallet->wltid }}</td>
                                <td class="p-2 text-left">{{ $wallet->exchange->name ?? 'N/A' }}</td>
                                <td class="p-2 text-left font-bold text-blue-600">${{ number_format($wallet->balance, 2) }}</td>
                                <td class="p-2 text-left">
                                    @if (session('is_admin'))
                                        <form method="POST" action="{{ route('wallets.toggleStatus', $wallet->wltid) }}" class="inline">
                                            @csrf
                                            @method('PATCH') <!-- This ensures Laravel recognizes it as a PATCH request -->
                                            <button type="submit" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                                                {{ $wallet->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                                {{ $wallet->status }}
                                            </button>
                                        </form>


                                        <!-- Show only status text on mobile -->
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden 
                                            {{ $wallet->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $wallet->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded 
                                            {{ $wallet->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $wallet->status }}
                                        </span>
                                    @endif
                                </td>
                                @if (session('is_admin'))
                                    <td class="hidden sm:block p-2 space-x-2 text-right">
                                        <form method="POST" action="{{ route('wallets.topup', $wallet->wltid) }}" class="inline">
                                            @csrf
                                            <input type="number" name="amount" step="0.01" required placeholder="Amount" class="border p-1 rounded w-24">
                                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Top Up</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $wallets->links() }}</div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="copy-notification" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden">
        Wallet ID Copied!
    </div>

    <!-- JavaScript -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                let notification = document.getElementById('copy-notification');
                notification.classList.remove('hidden');
                
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    </script>
</x-app-layout>
