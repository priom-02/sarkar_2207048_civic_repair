<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueSeeder extends Seeder
{
    public function run(): void
    {
        // Assumed IDs from previous seeders:
        // Users:      1=Super Admin, 2=Karim Admin, 3=Razia Mod, 4=Rahim Worker, 5=Jamal Worker
        //             6=Fatema, 7=Arif, 8=Nasrin, 9=Tariqul, 10=Sadia
        // Categories: 1=Broken Road, 2=Garbage, 3=Water, 4=Sewerage, 5=Lighting, 6=Electricity
        //             7=Traffic, 8=Tree, 9=Public Property, 10=Noise, 11=Footpath, 12=Other
        // Statuses:   1=Pending, 2=Acknowledged, 3=In Progress, 4=On Hold, 5=Resolved, 6=Closed, 7=Rejected
        // Areas:      1=Dhanmondi, 2=Mirpur, 3=Gulshan, 4=Banani, 5=Uttara, 6=Motijheel, 7=Old Dhaka

        $issues = [
            [
                'title'        => 'Large pothole on Road 27 causing accidents',
                'description'  => 'There is a very large pothole near house #12, Road 27, Dhanmondi. Several motorbikes have had accidents. Urgent repair needed.',
                'reported_by'  => 6,
                'category_id'  => 1,
                'area_id'      => 1,
                'status_id'    => 3,
                'latitude'     => 23.7460,
                'longitude'    => 90.3752,
                'upvote_count' => 47,
                'created_at'   => now()->subDays(15),
                'updated_at'   => now()->subDays(10),
            ],
            [
                'title'        => 'Overflowing garbage bins near Mirpur-10 roundabout',
                'description'  => 'The garbage bins near Mirpur-10 roundabout have not been collected for over a week. The smell is unbearable and attracting stray animals.',
                'reported_by'  => 7,
                'category_id'  => 2,
                'area_id'      => 2,
                'status_id'    => 2,
                'latitude'     => 23.8090,
                'longitude'    => 90.3670,
                'upvote_count' => 31,
                'created_at'   => now()->subDays(7),
                'updated_at'   => now()->subDays(5),
            ],
            [
                'title'        => 'Burst water main flooding Gulshan Avenue',
                'description'  => 'A water main pipe has burst on Gulshan Avenue near Gulshan 1 circle. Water is flooding the road and disrupting traffic.',
                'reported_by'  => 8,
                'category_id'  => 3,
                'area_id'      => 3,
                'status_id'    => 3,
                'latitude'     => 23.7930,
                'longitude'    => 90.4060,
                'upvote_count' => 62,
                'created_at'   => now()->subDays(3),
                'updated_at'   => now()->subDays(1),
            ],
            [
                'title'        => 'Sewage overflow on Banani Road 11',
                'description'  => 'Sewage is overflowing from the drain on Banani Road 11 near the school. Children are exposed to health hazards.',
                'reported_by'  => 9,
                'category_id'  => 4,
                'area_id'      => 4,
                'status_id'    => 1,
                'latitude'     => 23.7940,
                'longitude'    => 90.4080,
                'upvote_count' => 28,
                'created_at'   => now()->subDays(2),
                'updated_at'   => now()->subDays(2),
            ],
            [
                'title'        => 'Street lights not working in Uttara Sector 7',
                'description'  => 'Almost all street lights in Sector 7, Uttara have been out for 3 nights. The area is very dark and unsafe, especially for women.',
                'reported_by'  => 10,
                'category_id'  => 5,
                'area_id'      => 5,
                'status_id'    => 5,
                'latitude'     => 23.8750,
                'longitude'    => 90.3800,
                'upvote_count' => 89,
                'created_at'   => now()->subDays(20),
                'updated_at'   => now()->subDays(2),
            ],
            [
                'title'        => 'Transformer fire risk in Motijheel commercial area',
                'description'  => 'An old transformer in Motijheel is sparking and leaking oil. Very high fire risk. DESCO has not responded to calls.',
                'reported_by'  => 6,
                'category_id'  => 6,
                'area_id'      => 6,
                'status_id'    => 2,
                'latitude'     => 23.7330,
                'longitude'    => 90.4180,
                'upvote_count' => 54,
                'created_at'   => now()->subDays(1),
                'updated_at'   => now()->subHours(12),
            ],
            [
                'title'        => 'Traffic signal broken at Old Dhaka Chowk Bazaar',
                'description'  => 'The traffic signal at Chowk Bazaar intersection has been broken for 5 days causing massive traffic jams and near-miss accidents.',
                'reported_by'  => 7,
                'category_id'  => 7,
                'area_id'      => 7,
                'status_id'    => 1,
                'latitude'     => 23.7100,
                'longitude'    => 90.4080,
                'upvote_count' => 36,
                'created_at'   => now()->subDays(5),
                'updated_at'   => now()->subDays(5),
            ],
            [
                'title'        => 'Fallen tree blocking main road in Dhanmondi',
                'description'  => 'A large tree has fallen after last night\'s storm and is blocking Dhanmondi Road 15. Emergency clearance required.',
                'reported_by'  => 8,
                'category_id'  => 8,
                'area_id'      => 1,
                'status_id'    => 6,
                'latitude'     => 23.7471,
                'longitude'    => 90.3761,
                'upvote_count' => 19,
                'created_at'   => now()->subDays(10),
                'updated_at'   => now()->subDays(9),
            ],
            [
                'title'        => 'Broken footpath causing injuries near Mirpur school',
                'description'  => 'The footpath in front of Mirpur Government Primary School is severely broken with exposed rebar. Students have already been injured.',
                'reported_by'  => 9,
                'category_id'  => 11,
                'area_id'      => 2,
                'status_id'    => 4,
                'latitude'     => 23.8230,
                'longitude'    => 90.3660,
                'upvote_count' => 41,
                'created_at'   => now()->subDays(12),
                'updated_at'   => now()->subDays(8),
            ],
            [
                'title'        => 'Illegal garbage dumping near Gulshan Lake',
                'description'  => 'Construction waste is being illegally dumped near Gulshan Lake at night. This is destroying the lake environment.',
                'reported_by'  => 10,
                'category_id'  => 2,
                'area_id'      => 3,
                'status_id'    => 7,
                'latitude'     => 23.7960,
                'longitude'    => 90.4100,
                'upvote_count' => 23,
                'created_at'   => now()->subDays(30),
                'updated_at'   => now()->subDays(25),
            ],
        ];

        DB::table('issues')->insert($issues);
    }
}
