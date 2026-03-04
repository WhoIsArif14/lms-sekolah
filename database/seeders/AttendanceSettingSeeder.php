<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\AttendanceSetting::create([
            'start_time' => '07:00:00',
            'end_time' => '08:00:00',
            'is_open' => true,
            'latitude' => -6.200000, // Ganti koordinat sekolahmu
            'longitude' => 106.816666,
            'radius_meters' => 100,
        ]);
    }
}
