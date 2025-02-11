@props([
    'columns' => [], 
    'items' => [], 
    'statusToggleRoute' => null // Dynamic route for status toggle
])

@php
    $sortIcons = [
        'asc' => '⬆️', 
        'desc' => '⬇️',
    ];
    $currentSortBy = request('sortBy');
    $currentSortOrder = request('sortOrder') == 'asc' ? 'desc' : 'asc';
@endphp

<table class="w-full border-collapse">
    <!-- Table Header -->
    <thead>
        <tr class="text-[13px] sm:text-sm bg-gray-200 text-gray-600 uppercase leading-normal">
            @foreach ($columns as $index => $label)
                @php
                    $alignment = $loop->last ? 'text-right' : 'text-left';
                @endphp
                <th class="py-3 px-6 {{ $alignment }}">
                    @if (is_numeric($index))
                        {{-- Non-sortable column --}}
                        {{ $label }}
                    @else
                        {{-- Sortable column --}}
                        <a href="?sortBy={{ $index }}&sortOrder={{ $currentSortOrder }}">
                            {{ $label }}{!! $currentSortBy === $index ? $sortIcons[request('sortOrder')] ?? '' : '' !!}
                        </a>
                    @endif
                </th>
            @endforeach
        </tr>
    </thead>

    <!-- Table Body -->
    <tbody class="text-gray-600 text-sm">
        @foreach ($items as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                @foreach ($columns as $index => $label)
                    @php
                        $alignment = $loop->last ? 'text-right' : 'text-left';
                    @endphp
                    <td class="py-3 px-6 {{ $alignment }}">
                        @if ($index === 'status' && $statusToggleRoute)
                            @if (session('is_admin'))
                                <!-- Admin: Status Toggle Form -->
                                <form method="POST" action="{{ route($statusToggleRoute, $item->curid) }}" 
                                    class="inline hidden sm:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                                        {{ $item->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                        {{ $item->status }}
                                    </button>
                                </form>

                                <!-- Mobile: Read-only Status -->
                                <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden 
                                    {{ $item->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $item->status }}
                                </span>
                            @else
                                <!-- Non-admin: Read-only Status -->
                                <span class="px-2 py-1 text-white text-sm font-semibold rounded 
                                    {{ $item->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $item->status }}
                                </span>
                            @endif
                        @else
                            <!-- Other columns -->
                            {{ $item->$index ?? '' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
