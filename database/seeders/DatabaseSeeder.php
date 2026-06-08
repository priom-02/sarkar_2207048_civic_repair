<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            RoleSeeder::class,
            AreaSeeder::class,
            IssueCategorySeeder::class,
            IssueStatusSeeder::class,
            UserSeeder::class,
            IssueSeeder::class,
            IssueVoteSeeder::class,
            IssueAssignmentSeeder::class,
            StatusHistorySeeder::class,
            IssueCommentSeeder::class,
            NotificationSeeder::class,
        ]);

        // Also create a test user
        User::factory()->create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
