<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\ParentInfo;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-09-01',
                'end_date' => '2026-06-30',
                'is_current' => true,
            ]
        );

        // Sinflar
        $grade1 = Grade::firstOrCreate(
            ['name' => '1-sinf', 'academic_year_id' => $academicYear->id],
            ['level' => 1, 'monthly_fee' => 2500000]
        );

        $grade5 = Grade::firstOrCreate(
            ['name' => '5-sinf', 'academic_year_id' => $academicYear->id],
            ['level' => 5, 'monthly_fee' => 3000000]
        );

        $grade9 = Grade::firstOrCreate(
            ['name' => '9-sinf', 'academic_year_id' => $academicYear->id],
            ['level' => 9, 'monthly_fee' => 3500000]
        );

        // Sinf Rahbari
        $classTeacher = User::where('email', 'sinf.rahbari@school.uz')->first();

        // Bo'limlar
        $section1A = Section::firstOrCreate(
            ['name' => '1-A', 'grade_id' => $grade1->id],
            ['class_teacher_id' => $classTeacher?->id, 'capacity' => 25]
        );

        $section1B = Section::firstOrCreate(
            ['name' => '1-B', 'grade_id' => $grade1->id],
            ['class_teacher_id' => null, 'capacity' => 25]
        );

        $section5A = Section::firstOrCreate(
            ['name' => '5-A', 'grade_id' => $grade5->id],
            ['class_teacher_id' => null, 'capacity' => 30]
        );

        // Xarajat kategoriyalari
        $categories = [
            ['name' => 'Kommunal to\'lovlar (Elektr, Gaz, Suv)', 'description' => 'Oylik kommunal xizmatlar'],
            ['name' => 'O\'quv qurollari va kitoblar', 'description' => 'O\'quvchilar va darsliklar uchun'],
            ['name' => 'Oshxona va oziq-ovqat', 'description' => 'O\'quvchilar tushligi va mahsulotlar'],
            ['name' => 'Xo\'jalik xarajatlari', 'description' => 'Maktab tozaligi va ta\'mirlash'],
            ['name' => 'Marketing va reklama', 'description' => 'Target va reklama xarajatlari'],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Namunaviy o'quvchilar
        $sampleStudents = [
            [
                'student_id' => 'STD-2025-0001',
                'first_name' => 'Sardor',
                'last_name' => 'Azizov',
                'date_of_birth' => '2018-04-12',
                'gender' => 'male',
                'address' => 'Toshkent sh., Chilonzor tumani, 12-uy',
                'grade_id' => $grade1->id,
                'section_id' => $section1A->id,
                'academic_year_id' => $academicYear->id,
                'admission_date' => '2025-09-01',
                'status' => 'active',
                'parent' => [
                    'father_name' => 'Azizov Botir',
                    'father_phone' => '+998901110001',
                    'mother_name' => 'Azizova Nigora',
                    'mother_phone' => '+998901110002',
                    'address' => 'Toshkent sh., Chilonzor tumani, 12-uy',
                ],
            ],
            [
                'student_id' => 'STD-2025-0002',
                'first_name' => 'Madina',
                'last_name' => 'Karimova',
                'date_of_birth' => '2018-07-22',
                'gender' => 'female',
                'address' => 'Toshkent sh., Yunusobod tumani, 5-mavze',
                'grade_id' => $grade1->id,
                'section_id' => $section1A->id,
                'academic_year_id' => $academicYear->id,
                'admission_date' => '2025-09-01',
                'status' => 'active',
                'parent' => [
                    'father_name' => 'Karimov Jasur',
                    'father_phone' => '+998902220001',
                    'mother_name' => 'Karimova Gulnoza',
                    'mother_phone' => '+998902220002',
                    'address' => 'Toshkent sh., Yunusobod tumani, 5-mavze',
                ],
            ],
            [
                'student_id' => 'STD-2025-0003',
                'first_name' => 'Jasur',
                'last_name' => 'Tursunov',
                'date_of_birth' => '2018-02-15',
                'gender' => 'male',
                'address' => 'Toshkent sh., Mirzo Ulug\'bek tumani',
                'grade_id' => $grade1->id,
                'section_id' => $section1A->id,
                'academic_year_id' => $academicYear->id,
                'admission_date' => '2025-09-01',
                'status' => 'active',
                'parent' => [
                    'father_name' => 'Tursunov Shokir',
                    'father_phone' => '+998903330001',
                    'mother_name' => 'Tursunova Malika',
                    'mother_phone' => '+998903330002',
                    'address' => 'Toshkent sh., Mirzo Ulug\'bek tumani',
                ],
            ],
            [
                'student_id' => 'STD-2025-0004',
                'first_name' => 'Diyora',
                'last_name' => 'Rustamova',
                'date_of_birth' => '2018-11-05',
                'gender' => 'female',
                'address' => 'Toshkent sh., Yakkasaroy tumani',
                'grade_id' => $grade1->id,
                'section_id' => $section1A->id,
                'academic_year_id' => $academicYear->id,
                'admission_date' => '2025-09-01',
                'status' => 'active',
                'parent' => [
                    'father_name' => 'Rustamov Eldor',
                    'father_phone' => '+998904440001',
                    'mother_name' => 'Rustamova Umida',
                    'mother_phone' => '+998904440002',
                    'address' => 'Toshkent sh., Yakkasaroy tumani',
                ],
            ],
        ];

        foreach ($sampleStudents as $sData) {
            $parentData = $sData['parent'];
            unset($sData['parent']);

            $student = Student::firstOrCreate(
                ['student_id' => $sData['student_id']],
                $sData
            );

            ParentInfo::firstOrCreate(
                ['student_id' => $student->id],
                $parentData
            );
        }
    }
}
