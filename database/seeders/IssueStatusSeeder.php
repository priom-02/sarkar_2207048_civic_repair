<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'Pending',     'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Acknowledged', 'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'In Progress',  'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'On Hold',      'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Resolved',     'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Closed',       'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Rejected',     'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('issue_statuses')->insert($statuses);
    }
}
