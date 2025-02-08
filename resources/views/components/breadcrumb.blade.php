<!-- Breadcrumb Navigation -->
<nav class="mx-6 text-black mb-2 text-sm">
    <ol class="list-reset flex items-center">
        @php
            // Extract individual elements from the slot content
            preg_match_all('/<[^>]+>[^<]*<\/[^>]+>/', $slot, $matches);
            $links = $matches[0];
        @endphp

        @foreach ($links as $index => $link)
            @php
                // Add classes dynamically
                if (str_starts_with($link, '<a')) {
                    $link = str_replace('<a', '<a class="text-blue-500 hover:underline"', $link);
                } elseif (str_starts_with($link, '<span')) {
                    $link = str_replace('<span', '<span class="text-gray-500"', $link);
                }
            @endphp

            <li class="inline-block">{!! $link !!}</li>
            @if ($index < count($links) - 1)
                <li class="mx-2 text-gray-500">/</li>
            @endif
        @endforeach
    </ol>
</nav>
