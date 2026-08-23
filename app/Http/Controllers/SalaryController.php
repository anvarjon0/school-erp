<?php
namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $salaries = Salary::with(['user.roles', 'paidBy'])
            ->where('month', $month)
            ->where('year', $year)
            ->paginate(15);

        $totalNet = Salary::where('month', $month)->where('year', $year)->sum('net_salary');
        $totalPaid = Salary::where('month', $month)->where('year', $year)->where('status', 'paid')->sum('net_salary');

        return \Inertia\Inertia::render('Salaries/Index', [
            'salaries' => $salaries,
            'month' => $month,
            'year' => $year,
            'totalNet' => $totalNet,
            'totalPaid' => $totalPaid
        ]);
    }

    public function create()
    {
        $users = User::where('is_active', true)->with('roles')->get();
        return \Inertia\Inertia::render('Salaries/Create', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $bonus = $validated['bonus'] ?? 0;
        $deduction = $validated['deduction'] ?? 0;
        $netSalary = $user->base_salary + $bonus - $deduction;

        // Check if already exists
        $exists = Salary::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bu xodim uchun bu oyda maosh allaqachon hisoblangan.')
                ->withInput();
        }

        Salary::create([
            'user_id' => $validated['user_id'],
            'base_salary' => $user->base_salary,
            'bonus' => $bonus,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
            'month' => $validated['month'],
            'year' => $validated['year'],
            'status' => 'pending',
        ]);

        return redirect()->route('salaries.index', ['month' => $validated['month'], 'year' => $validated['year']])
            ->with('success', 'Maosh hisoblandi.');
    }

    public function edit(Salary $salary)
    {
        $salary->load('user');
        return \Inertia\Inertia::render('Salaries/Edit', [
            'salary' => $salary
        ]);
    }

    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
        ]);

        $bonus = $validated['bonus'] ?? 0;
        $deduction = $validated['deduction'] ?? 0;
        $netSalary = $salary->base_salary + $bonus - $deduction;

        $salary->update([
            'bonus' => $bonus,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
        ]);

        return redirect()->route('salaries.index', ['month' => $salary->month, 'year' => $salary->year])
            ->with('success', 'Maosh yangilandi.');
    }

    public function pay(Salary $salary)
    {
        $salary->update([
            'status' => 'paid',
            'paid_date' => now(),
            'paid_by' => auth()->id(),
        ]);

        return back()->with('success', 'Maosh to\'landi deb belgilandi.');
    }

    public function destroy(Salary $salary)
    {
        if ($salary->status === 'paid') {
            return back()->with('error', 'To\'langan maoshni o\'chirib bo\'lmaydi.');
        }
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Maosh o\'chirildi.');
    }
}
