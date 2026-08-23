<?php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();
        $sections = Section::with(['grade', 'classTeacher'])
            ->withCount('students')
            ->when($currentYear, function ($q) use ($currentYear) {
                $q->whereHas('grade', fn($gq) => $gq->where('academic_year_id', $currentYear->id));
            })
            ->get();
        return response()->json([
            'sections' => $sections
        ]);
    }

    public function create()
    {
        $currentYear = AcademicYear::current();
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();
        $teachers = User::whereHas('roles', fn($q) => $q->where('name', 'class-teacher'))->get();
        return response()->json([
            'grades' => $grades,
            'teachers' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
            'class_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        Section::create($validated);

        return response()->json(['message' => 'Bo\'lim muvaffaqiyatli yaratildi.']);
    }

    public function edit(Section $section)
    {
        $currentYear = AcademicYear::current();
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();
        $teachers = User::whereHas('roles', fn($q) => $q->where('name', 'class-teacher'))->get();
        return response()->json([
            'section' => $section,
            'grades' => $grades,
            'teachers' => $teachers
        ]);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
            'class_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        $section->update($validated);

        return response()->json(['message' => 'Bo\'lim yangilandi.']);
    }

    public function destroy(Section $section)
    {
        if ($section->students()->exists()) {
            return response()->json(['message' => 'Bu bo\'limda o\'quvchilar bor.']);
        }
        $section->delete();
        return response()->json(['message' => 'Bo\'lim o\'chirildi.']);
    }

    public function getByGrade(Grade $grade)
    {
        return response()->json($grade->sections()->select('id', 'name')->get());
    }
}
