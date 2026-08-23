<?php
namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')->get();
        return view('expense-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('expense-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Kategoriya yaratildi.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Kategoriya yangilandi.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->exists()) {
            return back()->with('error', 'Bu kategoriyaga xarajatlar biriktirilgan.');
        }
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Kategoriya o\'chirildi.');
    }
}
