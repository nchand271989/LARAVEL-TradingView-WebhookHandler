<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExchangeSeeder extends Seeder
{
    public function run()
    {
        DB::table('exchanges')->insert([
            'exid' => env('DELTA_EXCHANGE_INDIA', 100000000000110001),
            'name' => 'Delta Exchange India',
            'createdBy' => env('ADMIN_ID', 100000000000000001),
            'lastUpdatedBy' => env('ADMIN_ID', 100000000000000001),
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
