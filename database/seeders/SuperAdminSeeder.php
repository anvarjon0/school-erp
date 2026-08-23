<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@school.uz'],
            [
                'name' => 'Super Admin',
                'phone' => '+998901234567',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole && !$user->hasRole('super-admin')) {
            $user->roles()->attach($superAdminRole);
        }
    }
}
