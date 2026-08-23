<?php
namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')->get();
        return response()->json([
            'categories' => $categories
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
            'description' => 'nullable|string|max:500',
        ]);

        ExpenseCategory::create($validated);

        return response()->json(['message' => 'Kategoriya yaratildi.']);
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return response()->json([
            'expenseCategory' => $expenseCategory
        ]);
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $expenseCategory->update($validated);

        return response()->json(['message' => 'Kategoriya yangilandi.']);
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->exists()) {
            return response()->json(['message' => 'Bu kategoriyaga xarajatlar biriktirilgan.']);
        }
        $expenseCategory->delete();
        return response()->json(['message' => 'Kategoriya o\'chirildi.']);
    }
}
