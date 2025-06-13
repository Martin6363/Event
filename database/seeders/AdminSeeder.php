<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'full_name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $admin->assignRole('supervisor');
    }
}
