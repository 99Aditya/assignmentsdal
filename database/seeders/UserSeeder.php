<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $inputs = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin1234'),
                'ip' => $ip
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('admin1234'),
                'ip' => $ip

            ],
        ];

        User::insert($inputs);
    }
}
