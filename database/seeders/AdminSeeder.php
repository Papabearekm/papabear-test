<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
            'first_name' => 'PapaBear',
            'last_name' => 'Super Admin',
            'email' => 'superadmin@papabear.com',
            'password' => Hash::make('papabearsuper@2025'),
            'type' => 'admin',
            'status' => 1
            ],
            [
                'first_name' => 'PapaBear',
                'last_name' => 'Admin',
                'email' => 'admin@papabear.com',
                'password' => Hash::make('papabearadmin@2025'),
                'type' => 'tele',
                'status' => 1
            ]
        ];
        DB::table('users')->insert($user);
    }
}
