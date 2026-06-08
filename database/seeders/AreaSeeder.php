<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'area_name'        => 'Dhanmondi',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7461,
                'longitude_center' => 90.3742,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Mirpur',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.8223,
                'longitude_center' => 90.3654,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Gulshan',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7925,
                'longitude_center' => 90.4078,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Banani',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7937,
                'longitude_center' => 90.4066,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Uttara',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.8759,
                'longitude_center' => 90.3795,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Motijheel',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7330,
                'longitude_center' => 90.4170,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Old Dhaka',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7104,
                'longitude_center' => 90.4074,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Khulna Sadar',
                'city'             => 'Khulna',
                'latitude_center'  => 22.8456,
                'longitude_center' => 89.5403,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Agrabad',
                'city'             => 'Chattogram',
                'latitude_center'  => 22.3282,
                'longitude_center' => 91.8221,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'area_name'        => 'Rajshahi Sadar',
                'city'             => 'Rajshahi',
                'latitude_center'  => 24.3636,
                'longitude_center' => 88.6241,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ];

        DB::table('areas')->insert($areas);
    }
}
