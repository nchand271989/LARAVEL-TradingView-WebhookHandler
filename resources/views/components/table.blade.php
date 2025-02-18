@props([
    'columns' => [], 
    'items' => [], 
    'actions' => [],
    'detachRelations' => [], // New prop for detachRelations
    'editRoute' => null,
    'deleteRoute' => null,
    'topUpRoute' => null,
    'topUpWalletKey' => null,
    'key' => null,
])

@php
    $sortIcons = [
        'asc' => '⬆️', 
        'desc' => '⬇️',
    ];
    $currentSortBy = request('sortBy');
    $currentSortOrder = request('sortOrder') == 'asc' ? 'desc' : 'asc';
@endphp

<table class="table-fixed w-full border-collapse">
    <!-- Table Header -->
    <thead>
        <tr class="text-[13px] sm:text-sm bg-gray-200 text-gray-600 uppercase leading-normal">
            @foreach ($columns as $index => $label)
                @php
                    $alignment = $loop->last ? 'text-right' : 'text-left';
                @endphp

                <th class="py-3 px-6 {{ $alignment }} @if($index === 'currencies' || $index === 'webhook-url'  || $index === 'rules') hidden sm:table-cell @endif">
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
                    <td class="py-3 px-6 {{ $alignment }} @if($index === 'currencies' || $index === 'webhook-url'  || $index === 'rules') hidden sm:table-cell @endif">
                    @if ($index === 'name')
                        <span class="text-xs flex-wrap text-wrap">{{ $item->name }}<br/><i>({{ $item->{$key} }})</i></span>
                        @if($key === "webhid")
                            <div>
                                <small class="text-gray-500 cursor-pointer text-blue-500 underline" onclick="copyToClipboard('{{ url('/api/' . env('HOOK_KEY') . '/' . $item->createdBy . '/' . $item->webhid . '/' . $item->strategy_id . '/' . $item->exchange_id   . '/' . $item->currency_id . '/' . hash('sha256', env('HASH_KEY') . $item->createdBy . $item->webhid . $item->strategy_id)) }}', 'Webhook url copied')">
                                    Click here to copy URL
                                </small><br/>

                                <!-- Your HTML and Blade code -->
                                <small class="text-gray-500 cursor-pointer text-blue-500 underline" 
                                    onclick="getMessage('{{$item->webhid}}', '{{$item->name}}', '{{$item->strategy_id}}')">
                                    Click here to copy alert message
                                </small>

                                <!-- Script to handle link click and show message -->
                                <script>
                                    function getMessage(webhook_id, webhook_name, strategy_id) {
                                        // Message content
                                        const message = `{
                                            "Position Size": \{\{ strategy.position_size \}\},
                                            "Action": "\{\{ strategy.order.action \}\}",
                                            "Contracts": \{\{ strategy.order.contracts \}\},
                                            "Price": \{\{ strategy.order.price \}\},
                                            "Market Position": "\{\{ strategy.market_position \}\}",
                                            "Previous Market Position": "\{\{ strategy.prev_market_position \}\}",
                                            "Previous Market Position Size": \{\{ strategy.prev_market_position_size \}\},
                                            "Exchange": "\{\{ exchange \}\}",
                                            "Timeframe": "\{\{ interval \}\}",
                                            "Ticker": "\{\{ ticker \}\}",
                                            "Volume": \{\{ volume \}\},
                                            "webhookId": "${webhook_id}",
                                            "webhookName": "${webhook_name}",
                                            "strategyId": "${strategy_id}"
                                        }`;

                                        copyToClipboard(message, 'Alert message copied');
                                    }
                                </script>


                                <!-- Notification -->
                                <div id="copy-notification" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden">
                                    Webhook URL Copied!
                                </div>
                                <!-- JavaScript -->
                                <script>
                                    function copyToClipboard(content, msg) {
                                        navigator.clipboard.writeText(content).then(() => {
                                        let notification = document.getElementById('copy-notification');
                                        notification.classList.remove('hidden');
                                        notification.innerHTML=msg;                                
                                        
                                        // Hide notification after 2 seconds
                                        setTimeout(() => {
                                                notification.classList.add('hidden');
                                            }, 2000);
                                        }).catch(err => {
                                            console.error('Failed to copy:', err);
                                        });
                                    }
                                </script>
                            </div>
                        @endif
                    @elseif ($index=='rules')
                        <div class="p-2 bg-[#EEEEEE]">
                            @foreach ($item->rules as $rules => $rule)
                                @php
                                    $wallet = \App\Models\Wallet::where('rule_id', $rule->rid)
                                        ->where('webhook_id', $item->webhid)
                                        ->first();

                                    // Check if the wallet exists
                                    if ($wallet) {
                                        // Fetch the sum of 'Credit' transactions for the wallet
                                        $creditSum = \App\Models\Ledger::where('wallet_id', $wallet->wltid)
                                            ->where('transaction_type', 'Credit')
                                            ->sum('amount');

                                        // Fetch the sum of 'Debit' transactions for the wallet
                                            $debitSum = \App\Models\Ledger::where('wallet_id', $wallet->wltid)
                                                ->where('transaction_type', 'Debit')
                                                ->sum('amount');

                                        // Calculate the balance (Credits - Debits)
                                            $balance = $creditSum - $debitSum;
                                    } else {
                                        $balance = 0;  // Default balance if no wallet found
                                    }
                                @endphp
                                <div class="py-2 flex flex-col">
                                    <a href="/wallets?search={{ $wallet->wltid }}">
                                        <span class="text-xs"><i>{{ $rule->name }} - {{ $balance }}</i></span>
                                    </a>
                                    <form method="POST" action="{{ route($topUpRoute, $wallet->wltid) }}" class="inline">
                                        @csrf
                                        <input type="number" name="amount" step="0.01" required placeholder="Amount" class="border p-1 rounded w-24">
                                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Top Up</button>
                                    </form>
                                <div>
                            @endforeach
                        </div>
                        @elseif ($index === 'status')
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
