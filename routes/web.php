<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AccountController::class, 'index'])->name('account.index.index');
Route::get('/transaction/{account_id}', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/create/{account_id}', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/store/{account_id}', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/edit/{transaction}', [TransactionController::class, 'edit'])->name('transactions.edit');
Route::post('/update', [TransactionController::class, 'update'])->name('transactions.update');
Route::delete('/delete/{transaction_id}/{account_id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');