<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        // Status IDs: 1=Pending, 2=Acknowledged, 3=In Progress, 4=On Hold, 5=Resolved, 6=Closed, 7=Rejected
        // Admin=1, Moderator=3

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

        $history = [
            // Issue 1: Pending → Acknowledged → In Progress
            [
                'issue_id'      => 1,
                'old_status_id' => null,
                'new_status_id' => 1,
                'changed_by'    => $u[6],
                'remark'        => 'Issue reported by citizen.',
                'created_at'    => now()->subDays(15),
                'updated_at'    => now()->subDays(15),
            ],
            [
                'issue_id'      => 1,
                'old_status_id' => 1,
                'new_status_id' => 2,
                'changed_by'    => $u[3],
                'remark'        => 'Issue reviewed and acknowledged. Assigning to field team.',
                'created_at'    => now()->subDays(12),
                'updated_at'    => now()->subDays(12),
            ],
            [
                'issue_id'      => 1,
                'old_status_id' => 2,
                'new_status_id' => 3,
                'changed_by'    => $u[1],
                'remark'        => 'Worker assigned. Repair work started.',
                'created_at'    => now()->subDays(10),
                'updated_at'    => now()->subDays(10),
            ],

            // Issue 3: Pending → Acknowledged → In Progress
            [
                'issue_id'      => 3,
                'old_status_id' => null,
                'new_status_id' => 1,
                'changed_by'    => $u[8],
                'remark'        => 'Issue reported.',
                'created_at'    => now()->subDays(3),
                'updated_at'    => now()->subDays(3),
            ],
            [
                'issue_id'      => 3,
                'old_status_id' => 1,
                'new_status_id' => 2,
                'changed_by'    => $u[3],
                'remark'        => 'Urgent issue. WASA notified.',
                'created_at'    => now()->subDays(2),
                'updated_at'    => now()->subDays(2),
            ],
            [
                'issue_id'      => 3,
                'old_status_id' => 2,
                'new_status_id' => 3,
                'changed_by'    => $u[2],
                'remark'        => 'Emergency repair crew deployed.',
                'created_at'    => now()->subDays(1),
                'updated_at'    => now()->subDays(1),
            ],

            // Issue 5: Pending → Acknowledged → In Progress → Resolved → Closed
            [
                'issue_id'      => 5,
                'old_status_id' => null,
                'new_status_id' => 1,
                'changed_by'    => $u[10],
                'remark'        => 'Issue reported.',
                'created_at'    => now()->subDays(20),
                'updated_at'    => now()->subDays(20),
            ],
            [
                'issue_id'      => 5,
                'old_status_id' => 1,
                'new_status_id' => 2,
                'changed_by'    => $u[3],
                'remark'        => 'Acknowledged. Scheduling maintenance team.',
                'created_at'    => now()->subDays(19),
                'updated_at'    => now()->subDays(19),
            ],
            [
                'issue_id'      => 5,
                'old_status_id' => 2,
                'new_status_id' => 3,
                'changed_by'    => $u[1],
                'remark'        => 'Maintenance team assigned and on-site.',
                'created_at'    => now()->subDays(18),
                'updated_at'    => now()->subDays(18),
            ],
            [
                'issue_id'      => 5,
                'old_status_id' => 3,
                'new_status_id' => 5,
                'changed_by'    => $u[1],
                'remark'        => 'All 14 street lights replaced and functioning.',
                'created_at'    => now()->subDays(3),
                'updated_at'    => now()->subDays(3),
            ],
            [
                'issue_id'      => 5,
                'old_status_id' => 5,
                'new_status_id' => 6,
                'changed_by'    => $u[1],
                'remark'        => 'Confirmed resolved. Issue closed.',
                'created_at'    => now()->subDays(2),
                'updated_at'    => now()->subDays(2),
            ],

            // Issue 8: Pending → Resolved → Closed (emergency tree clearance)
            [
                'issue_id'      => 8,
                'old_status_id' => null,
                'new_status_id' => 1,
                'changed_by'    => $u[8],
                'remark'        => 'Emergency report: fallen tree blocking road.',
                'created_at'    => now()->subDays(10),
                'updated_at'    => now()->subDays(10),
            ],
            [
                'issue_id'      => 8,
                'old_status_id' => 1,
                'new_status_id' => 3,
                'changed_by'    => $u[1],
                'remark'        => 'Emergency clearance team dispatched immediately.',
                'created_at'    => now()->subDays(10),
                'updated_at'    => now()->subDays(10),
            ],
            [
                'issue_id'      => 8,
                'old_status_id' => 3,
                'new_status_id' => 6,
                'changed_by'    => $u[1],
                'remark'        => 'Tree cleared within 4 hours. Road open.',
                'created_at'    => now()->subDays(9),
                'updated_at'    => now()->subDays(9),
            ],

            // Issue 10: Pending → Rejected
            [
                'issue_id'      => 10,
                'old_status_id' => null,
                'new_status_id' => 1,
                'changed_by'    => $u[10],
                'remark'        => 'Issue reported.',
                'created_at'    => now()->subDays(30),
                'updated_at'    => now()->subDays(30),
            ],
            [
                'issue_id'      => 10,
                'old_status_id' => 1,
                'new_status_id' => 7,
                'changed_by'    => $u[3],
                'remark'        => 'Duplicate report. Case #3 already covers this lake area.',
                'created_at'    => now()->subDays(25),
                'updated_at'    => now()->subDays(25),
            ],
        ];

        DB::table('status_history')->insert($history);
    }
}
