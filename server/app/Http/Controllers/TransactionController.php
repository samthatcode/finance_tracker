<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getUserTransactions(Request $request)
    {
        try {
            $transactions = $request->user()->transactions()->latest()->get();
            return response()->json($transactions);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve transaction ' . $e->getMessage()], 500);
        }
    }

    public function createTransaction(Request $request)
    {
        try {
            $data = $request->validate([
                'type' => 'required|in:income,expense',
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'date' => 'required|date'
            ]);

            $transaction = $request->user()->transactions()->create($data);
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create transaction ' . $e->getMessage()], 500);
        }
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        try {
            if ($transaction->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $data = $request->validate([
                'type' => 'required|in:income,expense',
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'date' => 'required|date'
            ]);

            $transaction->update($data);
            return response()->json($transaction);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update transaction ' . $e->getMessage()], 500);
        }
    }

    public function deleteTransaction(Request $request, Transaction $transaction)
    {
        try {
            if ($transaction->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $transaction->delete();
            return response()->json(['message' => 'Transaction deleted']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete transaction ' . $e->getMessage()], 500);
        }
    }
}
