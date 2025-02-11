<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Scenarios</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <a href="{{ route('scenarios.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Create Scenario</a>
                <table class="w-full mt-4 border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Ratio</th>
                            <th class="p-2 text-left">Auto Exit</th>
                            <th class="p-2 text-left">Stop Loss</th>
                            <th class="p-2 text-left">Target Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scenarios as $scenario)
                            <tr>
                                <td class="p-2">{{ $scenario->name }}</td>
                                <td class="p-2">{{ $scenario->ratio ?? 'N/A' }}</td>
                                <td class="p-2">{{ $scenario->auto_exit ? 'Yes' : 'No' }}</td>
                                <td class="p-2">{{ $scenario->stop_loss ? 'Yes' : 'No' }}</td>
                                <td class="p-2">{{ $scenario->target_profit ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $scenarios->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
