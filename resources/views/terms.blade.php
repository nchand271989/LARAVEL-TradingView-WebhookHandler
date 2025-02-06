<x-guest-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div class="flex flex-col justify-center">
                <x-application-logo class="block h-[80px] w-auto" />
                <h1 class="mb-4 text-center text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Trading</span> BOT</h1>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                @if(empty($terms))
                    <p class="text-center text-gray-500">No Data Found</p>
                @else
                    {!! $terms !!}
                @endif
            </div>
        </div>
    </div>
    <x-footer />
</x-guest-layout>
