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
        if (app()->environment('production')) {
            $email = env('STAFF_EMAIL');
            $password = env('STAFF_PASSWORD');

            if (! is_string($email) || $email === '' || ! is_string($password) || strlen($password) < 12) {
                throw new \RuntimeException('Set STAFF_EMAIL and a STAFF_PASSWORD of at least 12 characters before seeding production.');
            }

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Resort Staff',
                    'password' => $password,
                ],
            );

            return;
        }

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
