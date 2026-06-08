<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'citizen',    'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'worker',     'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'moderator',  'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'admin',      'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);
    }
}
