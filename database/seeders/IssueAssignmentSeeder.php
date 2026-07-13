<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueAssignmentSeeder extends Seeder
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

        // Admin assigns workers to active issues
        $assignments = [
            [
                'issue_id'    => 1,  // pothole - in progress
                'worker_id'   => $u[4],
                'assigned_by' => $u[1],
                'notes'       => 'Priority repair. Use bituminous patching mix.',
                'created_at'  => now()->subDays(10),
                'updated_at'  => now()->subDays(10),
            ],
            [
                'issue_id'    => 2,  // garbage - acknowledged
                'worker_id'   => $u[5],
                'assigned_by' => $u[1],
                'notes'       => 'Clear bins and schedule twice-weekly collection for this zone.',
                'created_at'  => now()->subDays(4),
                'updated_at'  => now()->subDays(4),
            ],
            [
                'issue_id'    => 3,  // water main - in progress
                'worker_id'   => $u[4],
                'assigned_by' => $u[2],
                'notes'       => 'Coordinate with WASA. Temporary road closure may be needed.',
                'created_at'  => now()->subDays(1),
                'updated_at'  => now()->subDays(1),
            ],
            [
                'issue_id'    => 5,  // street lights - resolved
                'worker_id'   => $u[5],
                'assigned_by' => $u[1],
                'notes'       => 'Replace all 14 bulbs in Sector 7. Job completed.',
                'created_at'  => now()->subDays(18),
                'updated_at'  => now()->subDays(18),
            ],
            [
                'issue_id'    => 6,  // transformer - acknowledged
                'worker_id'   => $u[4],
                'assigned_by' => $u[2],
                'notes'       => 'Inspect and report. Contact DESCO if transformer replacement needed.',
                'created_at'  => now()->subHours(10),
                'updated_at'  => now()->subHours(10),
            ],
        ];

        DB::table('issue_assignments')->insert($assignments);
    }
}
