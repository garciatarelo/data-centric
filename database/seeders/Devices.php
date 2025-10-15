<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Devices extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('devices')->insert([
            [
                'serial' => 'SN123456',
                'brand' => 'Apple',
                'model' => 'iPad Pro',
                'type' => 'tablet',
                'imei' => '356789012345678',
                'status' => 'available',
                'notes' => 'New device, ready for assignment.',
            ],
            [
                'serial' => 'SN654321',
                'brand' => 'Samsung',
                'model' => 'Galaxy S21',
                'type' => 'phone',
                'imei' => '356789098765432',
                'status' => 'assigned',
                'notes' => 'Assigned to user John Doe.',
            ],
        ]);
    }
}
