<div class="container mt-5">
    <div class="row">
        @foreach($groupedOrders as $currency)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <!-- Primary Card for Currency -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-left p-4">
                        <h5 class="card-title text-xl text-[#0061ff] font-extrabold mb-0">{{ $currency['currencies'] }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($currency['webhooks'] as $webhook)
                            <!-- Nested Card for Webhook -->
                            <div class="card mb-2 shadow-sm border-light bg-[#FBFBF9]">
                                <div class="card-header bg-light p-3">
                                    <h6 class="card-title mb-0 text-[#009675]">{{ $webhook['webhook'] }}</h6>
                                </div>
                                <div class="card-body flex flex-wrap gap-4 p-4 ">
                                    @foreach($webhook['rules'] as $rule)
                                        <!-- Nested Card for Rule with Order Count and Balance -->
                                        <a href="{{ route('graph.index', ['wallet_id' => $rule['wallet_id']]) }}">
                                            <div class="card mb-3 shadow-sm border-light w-full sm:w-auto bg-[#F5F5F5]">
                                                <div class="card-header bg-light p-3">
                                                    <h6 class="card-title mb-0 text-[#008c02]">{{ $rule['rule'] }}</h6>
                                                </div>
                                                <div class="card-body p-3">
                                                    <p class="card-text">Number of Orders: <strong>{{ $rule['trade_count'] }}</strong></p>
                                                    <!-- Display the balance -->
                                                    <p class="card-text">
                                                        P&L: 
                                                        <strong style="color: {{ $rule['p&L'] < 0 ? 'red' : 'green' }}">
                                                            {{ number_format($rule['p&L'], 8) }}
                                                        </strong>
                                                    </p>


                                                    <p class="card-text">Balance: <strong>{{ $rule['balance'] }}</strong></p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
