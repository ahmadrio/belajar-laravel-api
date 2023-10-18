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
        User::factory()->create([
            'name' => 'Ahmad Rio',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true
        ]);

        User::factory()->count(50)->create();
    }
}
