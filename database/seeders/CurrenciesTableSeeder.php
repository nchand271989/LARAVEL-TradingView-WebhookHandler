<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurrenciesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('currencies')->insert([
            [
                'curid'         =>  env('BTC', 100000000000100001),
                'name'          =>  'Bitcoin',
                'shortcode'     =>  'BTC',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('ETH', 100000000000100002),
                'name'          =>  'Ethereum',
                'shortcode'     =>  'ETH',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('XRP', 100000000000100003),
                'name'          =>  'XRP',
                'shortcode'     =>  'XRP',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('USDT', 100000000000100004),
                'name'          =>  'Tether',
                'shortcode'     =>  'USDT',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('BNB', 100000000000100005),
                'name'          =>  'BNB',
                'shortcode'     =>  'BNB',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('SOL', 100000000000100006),
                'name'          =>  'Solana',
                'shortcode'     =>  'SOL',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('USDC', 100000000000100007),
                'name'          =>  'USDC',
                'shortcode'     =>  'USDC',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('DOGE', 100000000000100008),
                'name'          =>  'Dogecoin',
                'shortcode'     =>  'DOGE',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('ADA', 100000000000100009),
                'name'          =>  'Cardano',
                'shortcode'     =>  'ADA',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
            [
                'curid'         =>  env('TRX', 100000000000100010),
                'name'          =>  'TRON',
                'shortcode'     =>  'TRX',
                'createdBy'     =>  env('ADMIN_ID', 100000000000000001),
                'lastUpdatedBy' =>  env('ADMIN_ID', 100000000000000001),
                'status'        =>  'Active',
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now(),
            ],
        ]);
    }
}
