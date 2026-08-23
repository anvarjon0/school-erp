<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'creator']);

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('expense_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('expense_date', $request->year);
        }

        $expenses = $query->latest('expense_date')->paginate(15);
        $categories = ExpenseCategory::all();
        $totalAmount = $query->sum('amount');

        return \Inertia\Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'categories' => $categories,
            'totalAmount' => $totalAmount
        ]);
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        return \Inertia\Inertia::render('Expenses/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt_file' => 'nullable|file|max:5120',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('expenses', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Xarajat qo\'shildi.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        return \Inertia\Inertia::render('Expenses/Edit', [
            'expense' => $expense,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt_file' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('expenses', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Xarajat yangilandi.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Xarajat o\'chirildi.');
    }
}
