<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function getUserBudgets(Request $request)
    {
        try {
            $budgets = $request->user()->budgets()->get();
            return response()->json($budgets);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve budgets ' . $e->getMessage()], 500);
        }
    }

    public function createBudget(Request $request)
    {
        try {
            $data = $request->validate([
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'month' => 'required|string|regex:/^\\d{4}-\\d{2}$/'
            ]);

            $budget = $request->user()->budgets()->create($data);
            return response()->json($budget, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create budget ' . $e->getMessage()], 500);
        }
    }
}
