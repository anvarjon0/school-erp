<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentInfo;
use App\Models\Grade;
use App\Models\Section;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $currentYear = AcademicYear::current();

        $query = Student::with(['grade', 'section', 'academicYear']);

        // Class teacher can only see their own section students
        if ($user->hasRole('class-teacher') && !$user->isSuperAdmin()) {
            $sectionIds = $user->sections()->pluck('id');
            $query->whereIn('section_id', $sectionIds);
        }

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%")
                    ->orWhere('student_id', 'ilike', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(15);
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();

        return response()->json([
            'students' => $students,
            'grades' => $grades
        ]);
    }

    public function create()
    {
        $currentYear = AcademicYear::current();
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();
        $academicYears = AcademicYear::latest()->get();
        return response()->json([
            'grades' => $grades,
            'academicYears' => $academicYears,
            'currentYear' => $currentYear
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'admission_date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            // Parent info
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'parent_address' => 'nullable|string',
        ]);

        $studentData = collect($validated)->except([
            'father_name', 'father_phone', 'mother_name', 'mother_phone', 'parent_address', 'photo'
        ])->toArray();

        $studentData['student_id'] = Student::generateStudentId();

        if ($request->hasFile('photo')) {
            $studentData['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create($studentData);

        // Create parent info
        ParentInfo::create([
            'student_id' => $student->id,
            'father_name' => $validated['father_name'] ?? null,
            'father_phone' => $validated['father_phone'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'mother_phone' => $validated['mother_phone'] ?? null,
            'address' => $validated['parent_address'] ?? null,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'O\'quvchi muvaffaqiyatli qo\'shildi. ID: ' . $studentData['student_id']);
    }

    public function show(Student $student)
    {
        $student->load(['grade', 'section', 'academicYear', 'parentInfo', 'payments' => function ($q) {
            $q->latest()->take(10);
        }]);
        return response()->json([
            'student' => $student
        ]);
    }

    public function edit(Student $student)
    {
        $student->load('parentInfo');
        $currentYear = AcademicYear::current();
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();
        $sections = Section::where('grade_id', $student->grade_id)->get();
        $academicYears = AcademicYear::latest()->get();
        return response()->json([
            'student' => $student,
            'grades' => $grades,
            'sections' => $sections,
            'academicYears' => $academicYears
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'admission_date' => 'required|date',
            'status' => 'required|in:active,graduated,expelled,transferred',
            'photo' => 'nullable|image|max:2048',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'parent_address' => 'nullable|string',
        ]);

        $studentData = collect($validated)->except([
            'father_name', 'father_phone', 'mother_name', 'mother_phone', 'parent_address', 'photo'
        ])->toArray();

        if ($request->hasFile('photo')) {
            $studentData['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($studentData);

        $student->parentInfo()->updateOrCreate(
            ['student_id' => $student->id],
            [
                'father_name' => $validated['father_name'] ?? null,
                'father_phone' => $validated['father_phone'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_phone' => $validated['mother_phone'] ?? null,
                'address' => $validated['parent_address'] ?? null,
            ]
        );

        return response()->json(['message' => 'O\'quvchi ma\'lumotlari yangilandi.']);
    }

    public function destroy(Student $student)
    {
        if ($student->payments()->exists()) {
            return response()->json(['message' => 'Bu o\'quvchiga to\'lovlar biriktirilgan. Avval to\'lovlarni o\'chiring.']);
        }

        $student->parentInfo()?->delete();
        $student->delete();

        return response()->json(['message' => 'O\'quvchi o\'chirildi.']);
    }

    public function payments(Student $student)
    {
        $student->load(['payments' => fn($q) => $q->latest(), 'grade']);
        return response()->json([
            'student' => $student
        ]);
    }

    public function getBySection(Section $section)
    {
        return response()->json(
            $section->students()
                ->where('status', 'active')
                ->select('id', 'student_id', 'first_name', 'last_name')
                ->get()
        );
    }
}
