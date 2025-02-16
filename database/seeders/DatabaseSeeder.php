<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Seed the application's database. */
    public function run(): void
    {
        $this->call([
            PrivacyPoliciesTableSeeder::class,
            RulesTableSeeder::class,
            TermsAndConditionsTableSeeder::class,
            UsersTableSeeder::class,
            CurrenciesTableSeeder::class,
            ExchangeSeeder::class,
            ExchangeCurrencySeeder::class
        ]);
    }
}