<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => "Super Admin",
            'email' => "admin@fishingapp.com",
            'password' => Hash::make('password123'),
            'role' => 'super_admin'
        ]);

        Admin::create([
            'name' => "Admin",
            'email' => "admin2@fishingapp.com",
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]); 
    }
}
