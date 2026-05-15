<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command->warn('AdminSeeder: ADMIN_EMAIL or ADMIN_PASSWORD not set — skipping.');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'Admin',
                'password' => bcrypt($password),
                'role'     => 'admin',
            ]
        );

        $this->command->info("Admin user ensured: {$email}");
    }
}
