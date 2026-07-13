<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
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

        $notifications = [
            // Fatema (user 6) - reporter of issue 1 & 6
            [
                'user_id'    => $u[6],
                'issue_id'   => 1,
                'message'    => 'Your issue "Large pothole on Road 27" has been acknowledged.',
                'is_read'    => true,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id'    => $u[6],
                'issue_id'   => 1,
                'message'    => 'Your issue "Large pothole on Road 27" status changed to In Progress.',
                'is_read'    => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id'    => $u[6],
                'issue_id'   => 6,
                'message'    => 'Your issue "Transformer fire risk in Motijheel" has been acknowledged.',
                'is_read'    => false,
                'created_at' => now()->subHours(10),
                'updated_at' => now()->subHours(10),
            ],

            // Arif (user 7) - reporter of issue 2 & 7
            [
                'user_id'    => $u[7],
                'issue_id'   => 2,
                'message'    => 'Your issue "Overflowing garbage bins near Mirpur-10" has been acknowledged.',
                'is_read'    => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id'    => $u[7],
                'issue_id'   => 1,
                'message'    => 'An issue you upvoted "Large pothole on Road 27" is now In Progress.',
                'is_read'    => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],

            // Nasrin (user 8) - reporter of issue 3 & 8
            [
                'user_id'    => $u[8],
                'issue_id'   => 3,
                'message'    => 'Your issue "Burst water main flooding Gulshan Avenue" is now In Progress.',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id'    => $u[8],
                'issue_id'   => 8,
                'message'    => 'Your issue "Fallen tree blocking main road" has been Closed — tree cleared.',
                'is_read'    => true,
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],

            // Tariqul (user 9) - reporter of issue 4 & 9
            [
                'user_id'    => $u[9],
                'issue_id'   => 4,
                'message'    => 'Your issue "Sewage overflow on Banani Road 11" is still Pending. We apologize for the delay.',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],

            // Sadia (user 10) - reporter of issue 5 & 10
            [
                'user_id'    => $u[10],
                'issue_id'   => 5,
                'message'    => 'Your issue "Street lights not working in Uttara Sector 7" has been Resolved!',
                'is_read'    => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id'    => $u[10],
                'issue_id'   => 10,
                'message'    => 'Your issue "Illegal garbage dumping near Gulshan Lake" has been Rejected. Please see moderator comments.',
                'is_read'    => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],

            // Worker Rahim (user 4) - assignment notifications
            [
                'user_id'    => $u[4],
                'issue_id'   => 1,
                'message'    => 'You have been assigned to issue "Large pothole on Road 27". Please review and begin work.',
                'is_read'    => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id'    => $u[4],
                'issue_id'   => 3,
                'message'    => 'You have been assigned to urgent issue "Burst water main flooding Gulshan Avenue".',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        DB::table('notifications')->insert($notifications);
    }
}
