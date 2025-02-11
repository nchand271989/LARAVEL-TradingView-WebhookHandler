@props([
    'searchAction' => '', 
    'searchPlaceholder' => 'Search...',
    'buttonLabel' => 'Add Currency',
    'buttonRoute' => ''
])
<div class="w-full flex-col sm:flex-row flex items-end sm:justify-between sm:items-center gap-2 mb-4">
    <x-search action="{{ $searchAction }}" placeholder="{{ $searchPlaceholder }}" />

    @if (session('is_admin'))
        <a href="{{ $buttonRoute }}" class="hidden sm:block w-[140px] bg-green-500 text-white text-center px-4 py-2 rounded">{{ $buttonLabel }}</a>
    @endif
</div>