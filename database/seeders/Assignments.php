<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Assignments extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('assignments')->insert([
            [
                'device_id' => 1,
                'user_id' => 1,
                'assigned_by' => 1,
                'assigned_at' => now(),
                'returned_at' => null,
                'power_of_attorney' => 'POA123456',
                'qr_data' => 'QRDATA123456',
                'status' => 'active',
            ],
            [
                'device_id' => 2,
                'user_id' => 2,
                'assigned_by' => 1,
                'assigned_at' => now()->subDays(10),
                'returned_at' => now()->subDays(2),
                'power_of_attorney' => 'POA654321',
                'qr_data' => 'QRDATA654321',
                'status' => 'returned',
            ],
        ]);
    }
}
