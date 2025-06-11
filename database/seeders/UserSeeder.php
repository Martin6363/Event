<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Super Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'phone' => '00000000',
            'password' => bcrypt('123'),
        ]);
        
        $user->assignRole('supervisor');

        $user2 = User::create([
            'first_name' => 'Test User',
            'last_name' => 'Testyan',
            'email' => 'test@mail.ru',
            'phone' => '77777777',
            'password' => bcrypt('123'),
        ]);

        $user2->assignRole('user');
    }
}
