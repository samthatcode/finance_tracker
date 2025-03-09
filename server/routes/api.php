<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/', function () {
    return response()->json(['message' => 'Laravel API is Running']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register_user', [AuthController::class, 'registerUser']);
Route::post('/login_user', [AuthController::class, 'loginUser']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout_user', [AuthController::class, 'logoutUser']);
    Route::get('/get_user_transactions', [TransactionController::class, 'getUserTransactions']);
    Route::post('/create_transactions', [TransactionController::class, 'createTransaction']);
    Route::put('/update_transactions/{transaction}', [TransactionController::class, 'updateTransaction']);
    Route::delete('/delete_transactions/{transaction}', [TransactionController::class, 'deleteTransaction']);
    Route::get('/get_user_budgets', [BudgetController::class, 'getUserBudgets']);
    Route::post('/create_budgets', [BudgetController::class, 'createBudget']);
});
