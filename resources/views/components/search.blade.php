@props([
    'action' => '', 
    'placeholder' => 'Search...'
])
<form method="GET" action="{{ $action }}" class="flex w-full sm:w-auto space-x-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $placeholder }}" class="border rounded p-2 flex-1">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
</form>