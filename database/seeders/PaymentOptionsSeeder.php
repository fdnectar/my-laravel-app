<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_options')->insert([
            ['name' => 'Debit Card', 'description' => 'Pay via Debit Card', 'is_active' => true, 'code' => 'card'],
            ['name' => 'Cash On Delivery', 'description' => 'Pay using COD', 'is_active' => true, 'code' => 'cod']
        ]);
    }
}
