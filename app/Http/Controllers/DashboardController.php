<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\AcademicYear;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentYear = AcademicYear::current();
        $data = [];

        if ($user->isSuperAdmin() || $user->hasRole('administrator')) {
            $data['totalStudents'] = Student::where('status', 'active')->count();
            $data['totalUsers'] = User::count();
            $data['monthlyIncome'] = Payment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_amount');
            $data['monthlyExpense'] = Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount');
            $data['todayPayments'] = Payment::whereDate('created_at', today())->sum('paid_amount');
            $data['recentPayments'] = Payment::with('student')
                ->latest()
                ->take(5)
                ->get();
            $data['debtorCount'] = $this->getDebtorCount($currentYear);

            // Monthly income chart data (last 6 months)
            $data['chartLabels'] = [];
            $data['chartIncome'] = [];
            $data['chartExpense'] = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $data['chartLabels'][] = $date->translatedFormat('M Y');
                $data['chartIncome'][] = Payment::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('paid_amount');
                $data['chartExpense'][] = Expense::whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year)
                    ->sum('amount');
            }
        }

        if ($user->hasRole('accountant')) {
            $data['monthlyIncome'] = Payment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_amount');
            $data['monthlyExpense'] = Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount');
            $data['totalStudents'] = Student::where('status', 'active')->count();
            $data['debtorCount'] = $this->getDebtorCount($currentYear);
            $data['recentPayments'] = Payment::with('student')->latest()->take(5)->get();

            $data['chartLabels'] = [];
            $data['chartIncome'] = [];
            $data['chartExpense'] = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $data['chartLabels'][] = $date->translatedFormat('M Y');
                $data['chartIncome'][] = Payment::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('paid_amount');
                $data['chartExpense'][] = Expense::whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year)
                    ->sum('amount');
            }
        }

        if ($user->hasRole('class-teacher')) {
            $sectionIds = $user->sections()->pluck('id');
            $data['myStudents'] = Student::whereIn('section_id', $sectionIds)
                ->where('status', 'active')
                ->count();
            $data['todayAttendance'] = Attendance::whereIn('section_id', $sectionIds)
                ->whereDate('date', today())
                ->count();
            $data['todayPresent'] = Attendance::whereIn('section_id', $sectionIds)
                ->whereDate('date', today())
                ->where('status', 'present')
                ->count();
            $data['todayAbsent'] = Attendance::whereIn('section_id', $sectionIds)
                ->whereDate('date', today())
                ->where('status', 'absent')
                ->count();
        }

        return response()->json($data);
    }

    private function getDebtorCount(?AcademicYear $currentYear): int
    {
        if (!$currentYear) return 0;
        $currentMonth = now()->month;
        $currentYearNum = now()->year;

        $studentsWithPayments = Payment::where('academic_year_id', $currentYear->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYearNum)
            ->where('status', 'paid')
            ->pluck('student_id');

        return Student::where('status', 'active')
            ->where('academic_year_id', $currentYear->id)
            ->whereNotIn('id', $studentsWithPayments)
            ->count();
    }
}
