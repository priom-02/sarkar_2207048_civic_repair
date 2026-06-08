<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Role IDs depend on insertion order from RoleSeeder:
        // 1 = citizen, 2 = worker, 3 = moderator, 4 = admin

        $users = [
            // Admins
            [
                'full_name'         => 'Super Admin',
                'email'             => 'admin@civicplatform.bd',
                'password'          => Hash::make('Admin@1234'),
                'role_id'           => 4,
                'phone'             => '+8801700000001',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Karim Admin',
                'email'             => 'karim.admin@civicplatform.bd',
                'password'          => Hash::make('Admin@1234'),
                'role_id'           => 4,
                'phone'             => '+8801700000002',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],

            // Moderators
            [
                'full_name'         => 'Razia Moderator',
                'email'             => 'razia.mod@civicplatform.bd',
                'password'          => Hash::make('Mod@1234'),
                'role_id'           => 3,
                'phone'             => '+8801800000001',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],

            // Workers
            [
                'full_name'         => 'Rahim Worker',
                'email'             => 'rahim.worker@civicplatform.bd',
                'password'          => Hash::make('Worker@1234'),
                'role_id'           => 2,
                'phone'             => '+8801900000001',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Jamal Worker',
                'email'             => 'jamal.worker@civicplatform.bd',
                'password'          => Hash::make('Worker@1234'),
                'role_id'           => 2,
                'phone'             => '+8801900000002',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],

            // Citizens
            [
                'full_name'         => 'Fatema Begum',
                'email'             => 'fatema@gmail.com',
                'password'          => Hash::make('Password@123'),
                'role_id'           => 1,
                'phone'             => '+8801611111111',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Arif Hossain',
                'email'             => 'arif.hossain@gmail.com',
                'password'          => Hash::make('Password@123'),
                'role_id'           => 1,
                'phone'             => '+8801622222222',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Nasrin Akter',
                'email'             => 'nasrin.akter@yahoo.com',
                'password'          => Hash::make('Password@123'),
                'role_id'           => 1,
                'phone'             => '+8801633333333',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Tariqul Islam',
                'email'             => 'tariqul@hotmail.com',
                'password'          => Hash::make('Password@123'),
                'role_id'           => 1,
                'phone'             => '+8801644444444',
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'full_name'         => 'Sadia Rahman',
                'email'             => 'sadia.rahman@gmail.com',
                'password'          => Hash::make('Password@123'),
                'role_id'           => 1,
                'phone'             => null,
                'is_active'         => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
