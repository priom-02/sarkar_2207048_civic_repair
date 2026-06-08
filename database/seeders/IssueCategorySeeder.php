<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Broken Road / Pothole',
                'description'   => 'Damaged roads, potholes, cracked pavements, or uneven surfaces.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Garbage & Waste',
                'description'   => 'Illegal dumping, overflowing bins, or uncollected garbage.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Water Leakage / Supply',
                'description'   => 'Burst pipes, water leaks, low water pressure, or supply disruptions.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Sewerage & Drainage',
                'description'   => 'Blocked drains, sewage overflow, flooding due to poor drainage.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Street Lighting',
                'description'   => 'Non-functional or damaged street lights.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Electricity / Power',
                'description'   => 'Power outages, damaged power lines, or transformer issues.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Traffic & Signals',
                'description'   => 'Broken traffic signals, missing road signs, or signal timing issues.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Tree / Vegetation',
                'description'   => 'Fallen trees, overgrown vegetation blocking roads or utilities.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Public Property Damage',
                'description'   => 'Vandalism or damage to benches, fences, public buildings.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Noise & Air Pollution',
                'description'   => 'Excessive noise, illegal burning, industrial pollution.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Footpath / Pavement',
                'description'   => 'Broken or missing footpaths and pedestrian walkways.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_name' => 'Other',
                'description'   => 'Issues that do not fall under any specific category.',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        DB::table('issue_categories')->insert($categories);
    }
}
