<!-- resources/views/graph/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Exchanges') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Breadcrumb Navigation -->
                <x-breadcrumb>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="text-gray-500">Report</a>
                    <span class="text-gray-500">{{$wallet_id}}</span>
                </x-breadcrumb>                    
                    <!-- Google Chart container -->
                    <div id="chart_div" style="width: 100%; height: 500px;"></div>

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {
                            packages: ['corechart', 'line']
                        });

                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = new google.visualization.DataTable();
                            data.addColumn('string', 'Time');
                            data.addColumn('number', 'Balance');

                            // Passing the ledger data from PHP to JavaScript
                            var chartData = @json($chartData);

                            data.addRows(chartData);

                            var options = {
                                title: 'Balance Over Time',
                                curveType: 'function',
                                legend: { position: 'bottom' }
                            };

                            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                            chart.draw(data, options);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
