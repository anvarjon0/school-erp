<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Admin',
                'description' => 'Tizimning barcha bo\'limlarini boshqaradi',
            ],
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'O\'quvchilarni qabul qiladi va to\'lovlarni boshqaradi',
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Buxgalter',
                'description' => 'Moliyaviy hisobotlar, xarajatlar va oylik maoshlarni boshqaradi',
            ],
            [
                'name' => 'class-teacher',
                'display_name' => 'Sinf Rahbar',
                'description' => 'O\'z sinfi o\'quvchilarini boshqaradi va davomat yuritadi',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
