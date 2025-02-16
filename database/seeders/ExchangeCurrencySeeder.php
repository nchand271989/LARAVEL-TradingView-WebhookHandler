<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeCurrencySeeder extends Seeder
{
    public function run()
    {
        DB::table('exchange_currency')->insert([
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('BTC', 100000000000100001),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('ETH', 100000000000100002),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('XRP', 100000000000100003),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('USDT', 100000000000100004),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('BNB', 100000000000100005),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('SOL', 100000000000100006),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('USDC', 100000000000100007),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('DOGE', 100000000000100008),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('ADA', 100000000000100009),
            ],
            [
                'exchange_id' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
                'currency_id' => env('TRX', 100000000000100010),
            ],
        ]);
    }
}
