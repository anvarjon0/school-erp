<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $sections = $user->isSuperAdmin()
            ? Section::with('grade')->get()
            : $user->sections()->with('grade')->get();

        $attendances = collect();
        $selectedSection = null;
        $selectedDate = $request->get('date', today()->toDateString());

        if ($request->filled('section_id')) {
            $selectedSection = Section::find($request->section_id);
            $attendances = Attendance::with('student')
                ->where('section_id', $request->section_id)
                ->where('date', $selectedDate)
                ->get();
        }

        return response()->json([
            'sections' => $sections,
            'attendances' => $attendances,
            'selectedSection' => $selectedSection,
            'selectedDate' => $selectedDate
        ]);
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $sections = $user->isSuperAdmin()
            ? Section::with('grade')->get()
            : $user->sections()->with('grade')->get();

        $students = collect();
        $selectedSection = null;
        $date = $request->get('date', today()->toDateString());

        if ($request->filled('section_id')) {
            $selectedSection = Section::find($request->section_id);
            $students = Student::where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('last_name')
                ->get();

            // Get existing attendance for this date
            $existingAttendance = Attendance::where('section_id', $request->section_id)
                ->where('date', $date)
                ->pluck('status', 'student_id');

            foreach ($students as $student) {
                $student->attendance_status = $existingAttendance[$student->id] ?? null;
            }
        }

        return response()->json([
            'sections' => $sections,
            'students' => $students,
            'selectedSection' => $selectedSection,
            'date' => $date
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,excused',
        ]);

        foreach ($validated['attendance'] as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $validated['date'],
                ],
                [
                    'section_id' => $validated['section_id'],
                    'status' => $status,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('attendances.index', [
            'section_id' => $validated['section_id'],
            'date' => $validated['date'],
        ])->with('success', 'Davomat saqlandi.');
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        $sections = $user->isSuperAdmin()
            ? Section::with('grade')->get()
            : $user->sections()->with('grade')->get();

        $report = collect();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        if ($request->filled('section_id')) {
            $students = Student::where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('last_name')
                ->get();

            foreach ($students as $student) {
                $attendances = Attendance::where('student_id', $student->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get();

                $student->present_count = $attendances->where('status', 'present')->count();
                $student->absent_count = $attendances->where('status', 'absent')->count();
                $student->excused_count = $attendances->where('status', 'excused')->count();
                $student->total_days = $attendances->count();
            }

            $report = $students;
        }

        return response()->json([
            'sections' => $sections,
            'report' => $report,
            'month' => $month,
            'year' => $year
        ]);
    }
}
