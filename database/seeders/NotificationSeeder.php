<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            // Fatema (user 6) - reporter of issue 1 & 6
            [
                'user_id'    => 6,
                'issue_id'   => 1,
                'message'    => 'Your issue "Large pothole on Road 27" has been acknowledged.',
                'is_read'    => true,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id'    => 6,
                'issue_id'   => 1,
                'message'    => 'Your issue "Large pothole on Road 27" status changed to In Progress.',
                'is_read'    => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id'    => 6,
                'issue_id'   => 6,
                'message'    => 'Your issue "Transformer fire risk in Motijheel" has been acknowledged.',
                'is_read'    => false,
                'created_at' => now()->subHours(10),
                'updated_at' => now()->subHours(10),
            ],

            // Arif (user 7) - reporter of issue 2 & 7
            [
                'user_id'    => 7,
                'issue_id'   => 2,
                'message'    => 'Your issue "Overflowing garbage bins near Mirpur-10" has been acknowledged.',
                'is_read'    => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id'    => 7,
                'issue_id'   => 1,
                'message'    => 'An issue you upvoted "Large pothole on Road 27" is now In Progress.',
                'is_read'    => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],

            // Nasrin (user 8) - reporter of issue 3 & 8
            [
                'user_id'    => 8,
                'issue_id'   => 3,
                'message'    => 'Your issue "Burst water main flooding Gulshan Avenue" is now In Progress.',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id'    => 8,
                'issue_id'   => 8,
                'message'    => 'Your issue "Fallen tree blocking main road" has been Closed — tree cleared.',
                'is_read'    => true,
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],

            // Tariqul (user 9) - reporter of issue 4 & 9
            [
                'user_id'    => 9,
                'issue_id'   => 4,
                'message'    => 'Your issue "Sewage overflow on Banani Road 11" is still Pending. We apologize for the delay.',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],

            // Sadia (user 10) - reporter of issue 5 & 10
            [
                'user_id'    => 10,
                'issue_id'   => 5,
                'message'    => 'Your issue "Street lights not working in Uttara Sector 7" has been Resolved!',
                'is_read'    => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id'    => 10,
                'issue_id'   => 10,
                'message'    => 'Your issue "Illegal garbage dumping near Gulshan Lake" has been Rejected. Please see moderator comments.',
                'is_read'    => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],

            // Worker Rahim (user 4) - assignment notifications
            [
                'user_id'    => 4,
                'issue_id'   => 1,
                'message'    => 'You have been assigned to issue "Large pothole on Road 27". Please review and begin work.',
                'is_read'    => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id'    => 4,
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
