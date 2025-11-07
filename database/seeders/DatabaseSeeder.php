<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@jour.com',
            'email_verified_at' => now(),
            'password' => 'user123',
        ]);

        User::factory()->create([
            'name' => 'fend nugraha',
            'email' => 'fend@jour.com',
            'email_verified_at' => now(),
            'password' => 'user123',
        ]);
    }
}
