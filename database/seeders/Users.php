<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrador',
                'nickname' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '636-456-7890',
                'img' => 'admin.png',
                'role' => 'admin',
            ],
            [
                'name' => 'Pamela',
                'nickname' => 'Pame',
                'email' => 'pamela@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '636-654-3210',
                'img' => 'pamela.png',
                'role' => 'user',
            ],
        ]);
    }
}
