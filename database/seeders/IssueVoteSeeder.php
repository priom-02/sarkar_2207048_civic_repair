<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueVoteSeeder extends Seeder
{
    public function run(): void
    {
        // Spread votes across citizens (user IDs 6–10) and issues (1–10)
        // Must respect UNIQUE(issue_id, user_id) constraint

        $u = [
            1 => DB::table('users')->where('email', 'admin@civicplatform.bd')->first()->id,
            2 => DB::table('users')->where('email', 'admin@civicplatform.bd')->first()->id,
            3 => DB::table('users')->where('email', 'razia.mod@civicplatform.bd')->first()->id,
            4 => DB::table('users')->where('email', 'rahim.worker@civicplatform.bd')->first()->id,
            5 => DB::table('users')->where('email', 'jamal.worker@civicplatform.bd')->first()->id,
            6 => DB::table('users')->where('email', 'fatema@gmail.com')->first()->id,
            7 => DB::table('users')->where('email', 'arif.hossain@gmail.com')->first()->id,
            8 => DB::table('users')->where('email', 'nasrin.akter@yahoo.com')->first()->id,
            9 => DB::table('users')->where('email', 'tariqul@hotmail.com')->first()->id,
            10 => DB::table('users')->where('email', 'sadia.rahman@gmail.com')->first()->id,
        ];

        $votes = [
            // Issue 1 - pothole
            ['issue_id' => 1, 'user_id' => $u[7],  'created_at' => now()->subDays(14), 'updated_at' => now()->subDays(14)],
            ['issue_id' => 1, 'user_id' => $u[8],  'created_at' => now()->subDays(13), 'updated_at' => now()->subDays(13)],
            ['issue_id' => 1, 'user_id' => $u[9],  'created_at' => now()->subDays(12), 'updated_at' => now()->subDays(12)],
            ['issue_id' => 1, 'user_id' => $u[10], 'created_at' => now()->subDays(11), 'updated_at' => now()->subDays(11)],

            // Issue 2 - garbage
            ['issue_id' => 2, 'user_id' => $u[6],  'created_at' => now()->subDays(6),  'updated_at' => now()->subDays(6)],
            ['issue_id' => 2, 'user_id' => $u[8],  'created_at' => now()->subDays(6),  'updated_at' => now()->subDays(6)],
            ['issue_id' => 2, 'user_id' => $u[9],  'created_at' => now()->subDays(5),  'updated_at' => now()->subDays(5)],

            // Issue 3 - burst pipe (most votes)
            ['issue_id' => 3, 'user_id' => $u[6],  'created_at' => now()->subDays(3),  'updated_at' => now()->subDays(3)],
            ['issue_id' => 3, 'user_id' => $u[7],  'created_at' => now()->subDays(2),  'updated_at' => now()->subDays(2)],
            ['issue_id' => 3, 'user_id' => $u[9],  'created_at' => now()->subDays(2),  'updated_at' => now()->subDays(2)],
            ['issue_id' => 3, 'user_id' => $u[10], 'created_at' => now()->subDays(1),  'updated_at' => now()->subDays(1)],

            // Issue 4 - sewage
            ['issue_id' => 4, 'user_id' => $u[6],  'created_at' => now()->subDays(2),  'updated_at' => now()->subDays(2)],
            ['issue_id' => 4, 'user_id' => $u[7],  'created_at' => now()->subDays(1),  'updated_at' => now()->subDays(1)],
            ['issue_id' => 4, 'user_id' => $u[10], 'created_at' => now()->subDays(1),  'updated_at' => now()->subDays(1)],

            // Issue 5 - street lights (resolved, highest votes)
            ['issue_id' => 5, 'user_id' => $u[6],  'created_at' => now()->subDays(19), 'updated_at' => now()->subDays(19)],
            ['issue_id' => 5, 'user_id' => $u[7],  'created_at' => now()->subDays(18), 'updated_at' => now()->subDays(18)],
            ['issue_id' => 5, 'user_id' => $u[8],  'created_at' => now()->subDays(17), 'updated_at' => now()->subDays(17)],
            ['issue_id' => 5, 'user_id' => $u[9],  'created_at' => now()->subDays(16), 'updated_at' => now()->subDays(16)],

            // Issue 6 - transformer
            ['issue_id' => 6, 'user_id' => $u[7],  'created_at' => now()->subHours(20), 'updated_at' => now()->subHours(20)],
            ['issue_id' => 6, 'user_id' => $u[8],  'created_at' => now()->subHours(18), 'updated_at' => now()->subHours(18)],
            ['issue_id' => 6, 'user_id' => $u[9],  'created_at' => now()->subHours(15), 'updated_at' => now()->subHours(15)],
            ['issue_id' => 6, 'user_id' => $u[10], 'created_at' => now()->subHours(12), 'updated_at' => now()->subHours(12)],

            // Issue 7 - traffic signal
            ['issue_id' => 7, 'user_id' => $u[6],  'created_at' => now()->subDays(4),  'updated_at' => now()->subDays(4)],
            ['issue_id' => 7, 'user_id' => $u[8],  'created_at' => now()->subDays(3),  'updated_at' => now()->subDays(3)],
            ['issue_id' => 7, 'user_id' => $u[10], 'created_at' => now()->subDays(3),  'updated_at' => now()->subDays(3)],

            // Issue 9 - broken footpath
            ['issue_id' => 9, 'user_id' => $u[6],  'created_at' => now()->subDays(11), 'updated_at' => now()->subDays(11)],
            ['issue_id' => 9, 'user_id' => $u[7],  'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
            ['issue_id' => 9, 'user_id' => $u[10], 'created_at' => now()->subDays(9),  'updated_at' => now()->subDays(9)],
        ];

        DB::table('issue_votes')->insert($votes);
    }
}
