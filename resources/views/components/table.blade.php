@props([
    'columns' => [], 
    'items' => [], 
    'actions' => [],
    'detachRelations' => [], // New prop for detachRelations
    'editRoute' => null,
    'deleteRoute' => null,
    'topUpRoute' => null,
    'topUpWalletKey' => null,
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

                <th class="py-3 px-6 {{ $alignment }} @if($index === 'currencies') hidden sm:table-cell @endif">
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
                    <td class="py-3 px-6 {{ $alignment }} @if($index === 'currencies') hidden sm:table-cell @endif">
                        @if ($index === 'status')
                            @livewire('status-toggle', [
                                'model' => $item,
                                'actions' => $actions,
                                'detachRelations' => $detachRelations, // Pass detachRelations here
                                'editRoute' => $editRoute,
                                'deleteRoute' => $deleteRoute,
                            ])
                        @elseif ($index === 'currencies')
                            <div class="flex flex-wrap gap-2">
                                @foreach ($item->currencies->take(6) as $currency)
                                    <x-currency>{{ $currency->shortcode }}</x-currency>
                                @endforeach
                            </div>
                        @elseif (session('is_admin') && $index === 'topup')
                            <form method="POST" action="{{ route($topUpRoute, $item->$topUpWalletKey) }}" class="inline">
                                @csrf
                                <input type="number" name="amount" step="0.01" required placeholder="Amount" class="border p-1 rounded w-24">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Top Up</button>
                            </form>     
                        @else
                            @php
                            $value = $item;
                            foreach(explode('->', $index) as $segment) {
                                $value = $value->$segment ?? null;
                            }
                            @endphp
                            {{ $value ?? '' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
