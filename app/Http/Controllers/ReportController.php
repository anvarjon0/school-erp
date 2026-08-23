<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        $year = $request->get('year', now()->year);

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $income = Payment::whereMonth('created_at', $m)
                ->whereYear('created_at', $year)
                ->sum('paid_amount');
            $expense = Expense::whereMonth('expense_date', $m)
                ->whereYear('expense_date', $year)
                ->sum('amount');
            $salary = Salary::where('month', $m)
                ->where('year', $year)
                ->where('status', 'paid')
                ->sum('net_salary');

            $monthlyData[] = [
                'month' => $m,
                'income' => $income,
                'expense' => $expense,
                'salary' => $salary,
                'net' => $income - $expense - $salary,
            ];
        }

        $totalIncome = collect($monthlyData)->sum('income');
        $totalExpense = collect($monthlyData)->sum('expense');
        $totalSalary = collect($monthlyData)->sum('salary');

        return view('reports.financial', compact('monthlyData', 'year', 'totalIncome', 'totalExpense', 'totalSalary'));
    }

    public function monthlyIncome(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $payments = Payment::with(['student.grade'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        $totalAmount = $payments->sum('paid_amount');

        return view('reports.monthly-income', compact('payments', 'month', 'year', 'totalAmount'));
    }
}
