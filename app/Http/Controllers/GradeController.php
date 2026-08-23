<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();
        $grades = Grade::with('academicYear')
            ->withCount(['sections', 'students'])
            ->when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')
            ->get();
        return \Inertia\Inertia::render('Grades/Index', [
            'grades' => $grades
        ]);
    }

    public function create()
    {
        $academicYears = AcademicYear::latest()->get();
        return \Inertia\Inertia::render('Grades/Create', [
            'academicYears' => $academicYears
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:12',
            'monthly_fee' => 'required|numeric|min:0',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        Grade::create($validated);

        return redirect()->route('grades.index')
            ->with('success', 'Sinf muvaffaqiyatli yaratildi.');
    }

    public function edit(Grade $grade)
    {
        $academicYears = AcademicYear::latest()->get();
        return \Inertia\Inertia::render('Grades/Edit', [
            'grade' => $grade,
            'academicYears' => $academicYears
        ]);
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:12',
            'monthly_fee' => 'required|numeric|min:0',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $grade->update($validated);

        return redirect()->route('grades.index')
            ->with('success', 'Sinf yangilandi.');
    }

    public function destroy(Grade $grade)
    {
        if ($grade->students()->exists()) {
            return back()->with('error', 'Bu sinfda o\'quvchilar bor.');
        }
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Sinf o\'chirildi.');
    }
}
