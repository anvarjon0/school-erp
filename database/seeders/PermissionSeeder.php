<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'users.view', 'display_name' => 'Foydalanuvchilarni ko\'rish', 'group_name' => 'Foydalanuvchilar'],
            ['name' => 'users.create', 'display_name' => 'Foydalanuvchi yaratish', 'group_name' => 'Foydalanuvchilar'],
            ['name' => 'users.edit', 'display_name' => 'Foydalanuvchini tahrirlash', 'group_name' => 'Foydalanuvchilar'],
            ['name' => 'users.delete', 'display_name' => 'Foydalanuvchini o\'chirish', 'group_name' => 'Foydalanuvchilar'],

            // Roles
            ['name' => 'roles.view', 'display_name' => 'Rollarni ko\'rish', 'group_name' => 'Rollar'],
            ['name' => 'roles.create', 'display_name' => 'Rol yaratish', 'group_name' => 'Rollar'],
            ['name' => 'roles.edit', 'display_name' => 'Rolni tahrirlash', 'group_name' => 'Rollar'],
            ['name' => 'roles.delete', 'display_name' => 'Rolni o\'chirish', 'group_name' => 'Rollar'],

            // Academic Years
            ['name' => 'academic-years.view', 'display_name' => 'O\'quv yillarini ko\'rish', 'group_name' => 'O\'quv yillari'],
            ['name' => 'academic-years.create', 'display_name' => 'O\'quv yili yaratish', 'group_name' => 'O\'quv yillari'],
            ['name' => 'academic-years.edit', 'display_name' => 'O\'quv yilini tahrirlash', 'group_name' => 'O\'quv yillari'],
            ['name' => 'academic-years.delete', 'display_name' => 'O\'quv yilini o\'chirish', 'group_name' => 'O\'quv yillari'],

            // Grades
            ['name' => 'grades.view', 'display_name' => 'Sinflarni ko\'rish', 'group_name' => 'Sinflar'],
            ['name' => 'grades.create', 'display_name' => 'Sinf yaratish', 'group_name' => 'Sinflar'],
            ['name' => 'grades.edit', 'display_name' => 'Sinfni tahrirlash', 'group_name' => 'Sinflar'],
            ['name' => 'grades.delete', 'display_name' => 'Sinfni o\'chirish', 'group_name' => 'Sinflar'],

            // Sections
            ['name' => 'sections.view', 'display_name' => 'Bo\'limlarni ko\'rish', 'group_name' => 'Bo\'limlar'],
            ['name' => 'sections.create', 'display_name' => 'Bo\'lim yaratish', 'group_name' => 'Bo\'limlar'],
            ['name' => 'sections.edit', 'display_name' => 'Bo\'limni tahrirlash', 'group_name' => 'Bo\'limlar'],
            ['name' => 'sections.delete', 'display_name' => 'Bo\'limni o\'chirish', 'group_name' => 'Bo\'limlar'],

            // Students
            ['name' => 'students.view', 'display_name' => 'O\'quvchilarni ko\'rish', 'group_name' => 'O\'quvchilar'],
            ['name' => 'students.create', 'display_name' => 'O\'quvchi qo\'shish', 'group_name' => 'O\'quvchilar'],
            ['name' => 'students.edit', 'display_name' => 'O\'quvchini tahrirlash', 'group_name' => 'O\'quvchilar'],
            ['name' => 'students.delete', 'display_name' => 'O\'quvchini o\'chirish', 'group_name' => 'O\'quvchilar'],

            // Payments
            ['name' => 'payments.view', 'display_name' => 'To\'lovlarni ko\'rish', 'group_name' => 'To\'lovlar'],
            ['name' => 'payments.create', 'display_name' => 'To\'lov qabul qilish', 'group_name' => 'To\'lovlar'],
            ['name' => 'payments.edit', 'display_name' => 'To\'lovni tahrirlash', 'group_name' => 'To\'lovlar'],
            ['name' => 'payments.delete', 'display_name' => 'To\'lovni o\'chirish', 'group_name' => 'To\'lovlar'],

            // Expenses
            ['name' => 'expenses.view', 'display_name' => 'Xarajatlarni ko\'rish', 'group_name' => 'Xarajatlar'],
            ['name' => 'expenses.create', 'display_name' => 'Xarajat qo\'shish', 'group_name' => 'Xarajatlar'],
            ['name' => 'expenses.edit', 'display_name' => 'Xarajatni tahrirlash', 'group_name' => 'Xarajatlar'],
            ['name' => 'expenses.delete', 'display_name' => 'Xarajatni o\'chirish', 'group_name' => 'Xarajatlar'],

            // Salaries
            ['name' => 'salaries.view', 'display_name' => 'Maoshlarni ko\'rish', 'group_name' => 'Maoshlar'],
            ['name' => 'salaries.create', 'display_name' => 'Maosh hisoblash', 'group_name' => 'Maoshlar'],
            ['name' => 'salaries.edit', 'display_name' => 'Maoshni tahrirlash', 'group_name' => 'Maoshlar'],
            ['name' => 'salaries.delete', 'display_name' => 'Maoshni o\'chirish', 'group_name' => 'Maoshlar'],

            // Attendances
            ['name' => 'attendances.view', 'display_name' => 'Davomatni ko\'rish', 'group_name' => 'Davomat'],
            ['name' => 'attendances.create', 'display_name' => 'Davomat yuritish', 'group_name' => 'Davomat'],
            ['name' => 'attendances.edit', 'display_name' => 'Davomatni tahrirlash', 'group_name' => 'Davomat'],
            ['name' => 'attendances.delete', 'display_name' => 'Davomatni o\'chirish', 'group_name' => 'Davomat'],

            // Reports
            ['name' => 'reports.financial', 'display_name' => 'Moliyaviy hisobotlar', 'group_name' => 'Hisobotlar'],
            ['name' => 'reports.students', 'display_name' => 'O\'quvchilar hisoboti', 'group_name' => 'Hisobotlar'],
            ['name' => 'reports.attendance', 'display_name' => 'Davomat hisoboti', 'group_name' => 'Hisobotlar'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'Sozlamalarni ko\'rish', 'group_name' => 'Sozlamalar'],
            ['name' => 'settings.edit', 'display_name' => 'Sozlamalarni tahrirlash', 'group_name' => 'Sozlamalar'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // Assign all permissions to super-admin
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id'));
        }

        // Assign permissions to administrator
        $admin = Role::where('name', 'administrator')->first();
        if ($admin) {
            $adminPerms = Permission::whereIn('name', [
                'students.view', 'students.create', 'students.edit',
                'payments.view', 'payments.create', 'payments.edit',
                'academic-years.view',
                'grades.view',
                'sections.view',
                'reports.students',
            ])->pluck('id');
            $admin->permissions()->sync($adminPerms);
        }

        // Assign permissions to accountant
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $accPerms = Permission::whereIn('name', [
                'payments.view', 'payments.create', 'payments.edit',
                'expenses.view', 'expenses.create', 'expenses.edit', 'expenses.delete',
                'salaries.view', 'salaries.create', 'salaries.edit',
                'students.view',
                'reports.financial', 'reports.students',
                'academic-years.view',
                'grades.view',
                'sections.view',
            ])->pluck('id');
            $accountant->permissions()->sync($accPerms);
        }

        // Assign permissions to class-teacher
        $classTeacher = Role::where('name', 'class-teacher')->first();
        if ($classTeacher) {
            $teacherPerms = Permission::whereIn('name', [
                'students.view',
                'attendances.view', 'attendances.create', 'attendances.edit',
                'payments.view',
                'reports.attendance',
                'grades.view',
                'sections.view',
            ])->pluck('id');
            $classTeacher->permissions()->sync($teacherPerms);
        }
    }
}
