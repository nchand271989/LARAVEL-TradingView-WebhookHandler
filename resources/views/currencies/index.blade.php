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
            <nav class="mx-6 text-black mb-2 text-sm">
                <ol class="list-reset flex">
                    <li><a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><span class="text-blue-500 hover:underline">Markets & Assets</span></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-500">Currencies</li>
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="w-full flex-col sm:flex-row flex items-end sm:justify-between sm:items-center gap-2 mb-4">
                    <form method="GET" action="{{ route('currencies.index') }}" class="flex w-full sm:w-auto space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search currencies..." class="border rounded p-2 flex-1">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                    </form>

                    @if (session('is_admin'))
                        <a href="{{ route('currencies.create') }}" class="hidden sm:block w-[140px] bg-green-500 text-white text-center px-4 py-2 rounded">Add Currency</a>
                    @endif
                </div>

                <table class="table-fixed w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="text-[13px] sm:text-sm bg-gray-200 text-gray-600 uppercase leading-normal">
                            @php
                                $sortIcons = [
                                    'asc' => '⬆️', // Up arrow
                                    'desc' => '⬇️', // Down arrow
                                ];
                                $currentSortBy = request('sortBy');
                                $currentSortOrder = request('sortOrder') == 'asc' ? 'desc' : 'asc';
                            @endphp

                            <th class="py-3 px-6 text-left">
                                <a href="?sortBy=name&sortOrder={{ $currentSortOrder }}">
                                    Name{!! $currentSortBy === 'name' ? $sortIcons[request('sortOrder')] : '' !!}
                                </a>
                            </th>

                            <th class="py-3 px-6 text-left">
                                <a href="?sortBy=shortcode&sortOrder={{ $currentSortOrder }}">
                                    Code {!! $currentSortBy === 'shortcode' ? $sortIcons[request('sortOrder')] : '' !!}
                                </a>
                            </th>

                            <th class="py-3 px-6 text-right max-w-1/3">
                                <a href="?sortBy=status&sortOrder={{ $currentSortOrder }}">
                                    Status {!! $currentSortBy === 'status' ? $sortIcons[request('sortOrder')] : '' !!}
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($currencies as $currency)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $currency->name }}</td>
                                <td class="py-3 px-6">{{ $currency->shortcode }}</td>
                                <td class="py-3 px-6 text-right">
                                    @if (session('is_admin'))
                                        <!-- Hide PATCH form on small screens (mobile) -->
                                        <form method="POST" action="{{ route('currencies.toggleStatus', $currency->curid) }}" 
                                            class="inline hidden sm:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                                                {{ $currency->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                                {{ $currency->status }}
                                            </button>
                                        </form>

                                        <!-- Show only status text on mobile -->
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden 
                                            {{ $currency->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $currency->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-white text-sm font-semibold rounded 
                                            {{ $currency->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $currency->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $currencies->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
