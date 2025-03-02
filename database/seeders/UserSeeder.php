<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Faisal Khan',
            'phone_number' => 8898495502,
            'username' => 'faisal',
            'email' => 'faisal@email.com',
            'password' => Hash::make('123456')
        ]);
    }
}
