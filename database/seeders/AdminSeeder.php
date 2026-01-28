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
            'name' => 'Super Admin',
            'email' => 'joshuaadeyemi445@gmail.com',
            'password' => Hash::make('Gmail2025$'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);
    }
}