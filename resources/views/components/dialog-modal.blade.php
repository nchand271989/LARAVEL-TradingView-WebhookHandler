@props(['id' => null, 'maxWidth' => 'sm'])

<div id="{{ $id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 
                absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">{{ $title }}</h2>
            <p class="mt-2 text-sm text-gray-600">{{ $content }}</p>
        </div>
        <div class="flex justify-end px-6 py-2">
            {{ $footer }}
        </div>
    </div>
</div>
