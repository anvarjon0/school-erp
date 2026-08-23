<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@school.uz',
                'phone' => '+998901234567',
                'password' => Hash::make('password'),
                'base_salary' => 15000000,
                'is_active' => true,
                'role' => 'super-admin',
            ],
            [
                'name' => 'Jamshid Aliyev (Administrator)',
                'email' => 'administrator@school.uz',
                'phone' => '+998901112233',
                'password' => Hash::make('password'),
                'base_salary' => 8000000,
                'is_active' => true,
                'role' => 'administrator',
            ],
            [
                'name' => 'Dilnoza Karimova (Buxgalter)',
                'email' => 'buxgalter@school.uz',
                'phone' => '+998902223344',
                'password' => Hash::make('password'),
                'base_salary' => 10000000,
                'is_active' => true,
                'role' => 'accountant',
            ],
            [
                'name' => 'Rustam Qodirov (Sinf Rahbar)',
                'email' => 'sinf.rahbari@school.uz',
                'phone' => '+998903334455',
                'password' => Hash::make('password'),
                'base_salary' => 7000000,
                'is_active' => true,
                'role' => 'class-teacher',
            ],
        ];

        foreach ($users as $u) {
            $roleName = $u['role'];
            unset($u['role']);

            $user = User::updateOrCreate(
                ['email' => $u['email']],
                $u
            );

            $role = Role::where('name', $roleName)->first();
            if ($role && !$user->roles->contains($role->id)) {
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
