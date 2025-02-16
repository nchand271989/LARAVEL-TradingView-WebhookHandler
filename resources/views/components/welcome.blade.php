<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary font-weight-bold">Webhook Orders Dashboard</h2>

    <div class="row">
        @foreach($groupedOrders as $currencyName => $ordersByCurrency)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <!-- Primary Card for Currency -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-center p-4">
                        <h5 class="card-title mb-0">Currency: {{ $currencyName }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($ordersByCurrency->groupBy('webhook_name') as $webhookName => $ordersByWebhook)
                            <!-- Nested Card for Webhook -->
                            <div class="card mb-3 shadow-sm border-light">
                                <div class="card-header bg-light p-3">
                                    <h6 class="card-title mb-0">Webhook: {{ $webhookName }}</h6>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($ordersByWebhook as $order)
                                        <div class="card mb-3 shadow-sm border-light flex-none w-64"> <!-- Ensure each card has a fixed width -->
                                            <!-- Nested Card for Rule with Order Count -->
                                            <div class="card mb-3 shadow-sm border-light">
                                                <div class="card-header bg-light p-3">
                                                    <h6 class="card-title mb-0">Rule: {{ $order->rule_name }}</h6>
                                                </div>
                                                <div class="card-body p-3">
                                                    <p class="card-text">Number of Orders: <strong>{{ $order->order_count }}</strong></p>
                                                </div>
                                            </div>
                                        </div>
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
