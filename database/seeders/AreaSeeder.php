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
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Dhanmondi',
                'union_parishad'   => 'Ward 15',
                'area_name'        => 'Dhanmondi Ward 15',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7461,
                'longitude_center' => 90.3742,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Mirpur',
                'union_parishad'   => 'Ward 10',
                'area_name'        => 'Mirpur Ward 10',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.8223,
                'longitude_center' => 90.3654,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Gulshan',
                'union_parishad'   => 'Ward 19',
                'area_name'        => 'Gulshan Ward 19',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7925,
                'longitude_center' => 90.4078,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Banani',
                'union_parishad'   => 'Ward 20',
                'area_name'        => 'Banani Ward 20',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7937,
                'longitude_center' => 90.4066,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Uttara',
                'union_parishad'   => 'Ward 1',
                'area_name'        => 'Uttara Ward 1',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.8759,
                'longitude_center' => 90.3795,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Motijheel',
                'union_parishad'   => 'Ward 9',
                'area_name'        => 'Motijheel Ward 9',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7330,
                'longitude_center' => 90.4170,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Dhaka',
                'district'         => 'Dhaka',
                'upazila'          => 'Kotwali',
                'union_parishad'   => 'Ward 32',
                'area_name'        => 'Old Dhaka Ward 32',
                'city'             => 'Dhaka',
                'latitude_center'  => 23.7104,
                'longitude_center' => 90.4074,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Khulna',
                'district'         => 'Khulna',
                'upazila'          => 'Khulna Sadar',
                'union_parishad'   => 'Ward 30',
                'area_name'        => 'Khulna Sadar Ward 30',
                'city'             => 'Khulna',
                'latitude_center'  => 22.8456,
                'longitude_center' => 89.5403,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Chattogram',
                'district'         => 'Chattogram',
                'upazila'          => 'Double Mooring',
                'union_parishad'   => 'Agrabad Ward 24',
                'area_name'        => 'Agrabad Ward 24',
                'city'             => 'Chattogram',
                'latitude_center'  => 22.3282,
                'longitude_center' => 91.8221,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'division'         => 'Rajshahi',
                'district'         => 'Rajshahi',
                'upazila'          => 'Boalia',
                'union_parishad'   => 'Ward 12',
                'area_name'        => 'Rajshahi Sadar Ward 12',
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
