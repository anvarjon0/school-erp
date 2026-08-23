<?php
namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::withCount('students')->latest()->get();
        return response()->json([
            'academicYears' => $academicYears
        ]);
    }

    public function create()
    {
        return response()->json(['message' => 'Success']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicYear::create($validated);

        return response()->json(['message' => 'O\'quv yili muvaffaqiyatli yaratildi.']);
    }

    public function edit(AcademicYear $academicYear)
    {
        return response()->json([
            'academicYear' => $academicYear
        ]);
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $academicYear->update($validated);

        return response()->json(['message' => 'O\'quv yili yangilandi.']);
    }

    public function setCurrent(AcademicYear $academicYear)
    {
        AcademicYear::where('is_current', true)->update(['is_current' => false]);
        $academicYear->update(['is_current' => true]);

        return response()->json(['message' => 'Joriy o\'quv yili o\'zgartirildi.']);
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->students()->exists()) {
            return response()->json(['message' => 'Bu o\'quv yiliga o\'quvchilar biriktirilgan.']);
        }
        $academicYear->delete();
        return response()->json(['message' => 'O\'quv yili o\'chirildi.']);
    }
}
