<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Markets & Assets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-breadcrumb>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="text-gray-500">Markets & Assets</span>
            </x-breadcrumb>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Exchanges Section -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">🌍 Exchanges</h3>
                        @if (session('is_admin'))
                            <a href="{{ route('exchanges.create') }}" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                + Add Exchange
                            </a>
                        @endif
                    </div>
                    <p class="text-gray-600 mt-3 text-sm">
                        Manage different exchanges and their available currencies. Track and organize various trading platforms where digital assets are traded.
                    </p>
                    <ul class="mt-3 text-sm text-gray-700 space-y-2">
                        <li>💼 <b>Centralized & Decentralized Platforms</b> – Keep records of both traditional and decentralized exchanges.</li>
                        <li>🔄 <b>Active & Inactive Status</b> – Enable or disable exchanges based on availability and requirements.</li>
                        <li>💱 <b>Linked Currencies</b> – Associate only supported currencies with each exchange.</li>
                        <li>📊 <b>Market Insights</b> – Track active exchanges and supported currencies.</li>
                    </ul>

                    <!-- View All Button at Bottom -->
                    <div class="mt-4">
                        <a href="{{ route('exchanges.index') }}" class="text-blue-500 hover:underline flex items-center">
                            View All Exchanges →
                        </a>
                    </div>
                </div>

                <!-- Currencies Section -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">💰 Currencies</h3>
                        @if (session('is_admin'))
                            <a href="{{ route('currencies.create') }}" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                + Add Currency
                            </a>
                        @endif
                    </div>
                    <p class="text-gray-600 mt-3 text-sm">
                        Manage different digital currencies supported in exchanges. Maintain accurate records of active currencies.
                    </p>
                    <ul class="mt-3 text-sm text-gray-700 space-y-2">
                        <li>🔍 <b>Search & Filter</b> – Quickly find and manage currencies by name or shortcode.</li>
                        <li>🛠️ <b>Status Management</b> – Activate or deactivate currencies as per business needs.</li>
                        <li>🌎 <b>Global Support</b> – Maintain a diverse range of assets from various markets.</li>
                        <li>📈 <b>Live Market Integration</b> – Easily update currency data with real-time market changes.</li>
                    </ul>

                    <!-- View All Button at Bottom -->
                    <div class="mt-4">
                        <a href="{{ route('currencies.index') }}" class="text-blue-500 hover:underline flex items-center">
                            View All Currencies →
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
