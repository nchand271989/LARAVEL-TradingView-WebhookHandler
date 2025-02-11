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
                <div class="w-full flex-col sm:flex-row flex items-end sm:justify-between sm:items-center gap-2 mb-4">
                    <form method="GET" action="{{ route('exchanges.index') }}" class="flex w-full sm:w-auto space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exchanges..." class="border rounded p-2 flex-1">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                    </form>

                    @if (session('is_admin'))
                        <a href="{{ route('exchanges.create') }}" class="hidden sm:block w-[140px] bg-green-500 text-white text-center px-4 py-2 rounded">Add Exchange</a>
                    @endif
                </div>

                <table class="table-fixed w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="text-[13px] sm:text-sm bg-gray-200 text-gray-600 uppercase leading-normal">
                            @php
                                $sortIcons = [
                                    'asc' => '⬆️', 
                                    'desc' => '⬇️',
                                ];
                                $currentSortBy = request('sortBy');
                                $currentSortOrder = request('sortOrder') == 'asc' ? 'desc' : 'asc';
                            @endphp

                            <th class="py-3 px-6 text-left">
                                <a href="?sortBy=name&sortOrder={{ $currentSortOrder }}">
                                    Name{!! $currentSortBy === 'name' ? $sortIcons[request('sortOrder')] : '' !!}
                                </a>
                            </th>

                            <th class="py-3 px-6 text-left hidden sm:block">Currencies</th>

                            <th class="py-3 px-6 text-right">
                                <a href="?sortBy=status&sortOrder={{ $currentSortOrder }}">
                                    Status {!! $currentSortBy === 'status' ? $sortIcons[request('sortOrder')] : '' !!}
                                </a>
                            </th>
                            @if (session('is_admin'))
                                <th class="hidden sm:block py-3 px-6 text-right hidden sm:block">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($exchanges as $exchange)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $exchange->name }}</td>
                                <td class="py-3 px-6 hidden sm:block">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($exchange->currencies->take(6) as $currency)
                                            <x-currency>{{ $currency->shortcode }}</x-currency>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    @if (session('is_admin'))
                                        <form method="POST" action="{{ route('exchanges.toggleStatus', $exchange->exid) }}" 
                                        class="inline hidden sm:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                                                {{ $exchange->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                                {{ $exchange->status }}
                                            </button>
                                        </form>

                                        <!-- Show only status text on mobile -->
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden 
                                            {{ $exchange->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $exchange->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-white text-sm font-semibold
                                            {{ $exchange->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $exchange->status }}
                                        </span>
                                    @endif    
                                </td>
                                @if (session('is_admin'))
                                <td class="hidden sm:table-cell py-3 px-6 text-right">
                                    @if ($exchange->status === 'Inactive')
                                        <a href="{{ route('exchanges.edit', $exchange->exid) }}" class="text-blue-500 hover:underline">Edit</a>
                                    @endif
                                </td>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $exchanges->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
