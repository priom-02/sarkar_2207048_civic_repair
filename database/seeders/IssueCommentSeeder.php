<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueCommentSeeder extends Seeder
{
    public function run(): void
    {
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

        $comments = [
            // Issue 1 - pothole
            [
                'issue_id'    => 1,
                'user_id'     => $u[7],
                'body'        => 'My bike tyre burst in this exact pothole last week. This is very dangerous!',
                'is_internal' => false,
                'created_at'  => now()->subDays(14),
                'updated_at'  => now()->subDays(14),
            ],
            [
                'issue_id'    => 1,
                'user_id'     => $u[3],
                'body'        => 'Issue acknowledged. We have scheduled a site inspection for tomorrow morning.',
                'is_internal' => false,
                'created_at'  => now()->subDays(12),
                'updated_at'  => now()->subDays(12),
            ],
            [
                'issue_id'    => 1,
                'user_id'     => $u[1],
                'body'        => 'Internal note: Materials requisition #REQ-2024-441 submitted. Expected delivery in 3 days.',
                'is_internal' => true,
                'created_at'  => now()->subDays(11),
                'updated_at'  => now()->subDays(11),
            ],
            [
                'issue_id'    => 1,
                'user_id'     => $u[4],
                'body'        => 'Work started today. Will take approximately 2 days to complete the patching.',
                'is_internal' => false,
                'created_at'  => now()->subDays(10),
                'updated_at'  => now()->subDays(10),
            ],

            // Issue 2 - garbage
            [
                'issue_id'    => 2,
                'user_id'     => $u[8],
                'body'        => 'The situation has gotten worse since yesterday. Please send a truck urgently.',
                'is_internal' => false,
                'created_at'  => now()->subDays(6),
                'updated_at'  => now()->subDays(6),
            ],
            [
                'issue_id'    => 2,
                'user_id'     => $u[3],
                'body'        => 'Collection truck has been rescheduled. Should arrive by this afternoon.',
                'is_internal' => false,
                'created_at'  => now()->subDays(5),
                'updated_at'  => now()->subDays(5),
            ],

            // Issue 3 - water main
            [
                'issue_id'    => 3,
                'user_id'     => $u[9],
                'body'        => 'My office building has had no water since this morning because of this burst.',
                'is_internal' => false,
                'created_at'  => now()->subDays(3),
                'updated_at'  => now()->subDays(3),
            ],
            [
                'issue_id'    => 3,
                'user_id'     => $u[10],
                'body'        => 'Traffic has been diverted. This is causing major delays on my daily commute.',
                'is_internal' => false,
                'created_at'  => now()->subDays(2),
                'updated_at'  => now()->subDays(2),
            ],
            [
                'issue_id'    => 3,
                'user_id'     => $u[2],
                'body'        => 'Emergency crew on site. Estimated fix time: 6-8 hours. Water tanker arranged for affected buildings.',
                'is_internal' => false,
                'created_at'  => now()->subDays(1),
                'updated_at'  => now()->subDays(1),
            ],

            // Issue 5 - street lights (resolved)
            [
                'issue_id'    => 5,
                'user_id'     => $u[6],
                'body'        => 'The lights are finally fixed! Thank you to the team. Sector 7 feels safe again.',
                'is_internal' => false,
                'created_at'  => now()->subDays(2),
                'updated_at'  => now()->subDays(2),
            ],

            // Issue 6 - transformer
            [
                'issue_id'    => 6,
                'user_id'     => $u[7],
                'body'        => 'I can hear buzzing and see sparks from my office window. This is extremely dangerous.',
                'is_internal' => false,
                'created_at'  => now()->subHours(20),
                'updated_at'  => now()->subHours(20),
            ],
            [
                'issue_id'    => 6,
                'user_id'     => $u[3],
                'body'        => 'DESCO emergency line has been notified. Worker dispatched for initial assessment.',
                'is_internal' => false,
                'created_at'  => now()->subHours(15),
                'updated_at'  => now()->subHours(15),
            ],
        ];

        DB::table('issue_comments')->insert($comments);
    }
}
