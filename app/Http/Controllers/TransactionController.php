<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionType as TransactionTypeModel;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    public function index($account)
    {
        $account = Account::whereId($account)->with('transactions')->first();
        return view('transactions.index', ['account' => $account]);
    }

    public function create($account)
    {
        $transactionTypes = TransactionTypeModel::all();
        return view('transactions.create', ['transactionTypes' => $transactionTypes, 'accountId' => $account]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'transaction_type' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $account = Account::whereId($request->account_id)->with('transactions')->first();

        $lastTransaction = $account->transactions()->orderBy('created_at', 'desc')->first();

        if(!$lastTransaction) {
            $lastTransaction = new Transaction();
            $lastTransaction->previous_balance = 0;
            $lastTransaction->balance_after = 0;
            $lastTransaction->invoice_total_amount = 0;
        }

        $transaction = match ($request->transaction_type) {
            TransactionType::discount->value => $this->applyDiscount($request, $lastTransaction),
            TransactionType::deposit->value => $this->applyCredit($request, $lastTransaction),
            TransactionType::payment->value => $this->processPayment($request, $lastTransaction),
            TransactionType::credit->value => $this->applyCredit($request, $lastTransaction),
            TransactionType::refund->value => $this->applyCredit($request, $lastTransaction),
        };

        $newTransaction = $account->transactions()->create($transaction);
        $account->available_balance = $transaction['balance_after'];
        $account->save();

        $this->sentNotification($newTransaction, $account);

        return redirect()->route('transactions.index', $account->id);
    }

    public function applyDiscount(Request $request, Transaction $lastTransaction): array
    {
        return [
            'reference' => 'TXN-' . uniqid(),
            'transaction_type_id' => $request->transaction_type,
            'amount' => $request->amount,
            'previous_balance' => $lastTransaction->previous_balance,
            'balance_after' => $lastTransaction->balance_after,
            'invoice_total_amount' => $lastTransaction->invoice_total_amount - $request->amount,
        ];
    }

    public function processPayment(Request $request, Transaction $lastTransaction): array
    {
        return [
            'reference' => 'TXN-' . uniqid(),
            'transaction_type_id' => $request->transaction_type,
            'amount' => $request->amount,
            'previous_balance' => $lastTransaction->balance_after,
            'balance_after' => $lastTransaction->balance_after - $request->amount,
            'invoice_total_amount' => $lastTransaction->invoice_total_amount,
        ];
    }

    public function applyCredit(Request $request, Transaction $lastTransaction): array
    {
        return [
            'reference' => 'TXN-' . uniqid(),
            'transaction_type_id' => $request->transaction_type,
            'amount' => $request->amount,
            'previous_balance' => $lastTransaction->balance_after,
            'balance_after' => $lastTransaction->balance_after + $request->amount,
            'invoice_total_amount' => $lastTransaction->invoice_total_amount,
        ];
    }

    public function sentNotification(Transaction $transaction, Account $account): void
    {
        Notification::send($account, new TransactionNotification($transaction));
    }

    public function destroy($transactionId,$accountId)
    {

        // Improvement
        // Need to recalculate the account balance based on deleted transaction//
        // Add confirmation modal before deleting a transaction //

        $transaction = Transaction::findOrFail($transactionId);
        $transaction->delete();

        return redirect()->route('transactions.index', $accountId);
    }

    public function edit(Transaction $transaction)
    {
        $transactionTypes = TransactionTypeModel::all();
        return view('transactions.edit', ['transaction' => $transaction, 'transactionTypes' => $transactionTypes]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'transaction_type_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'previous_balance' => 'required|numeric',
            'balance_after' => 'required|numeric',
            'invoice_total_amount' => 'required|numeric',
        ]);

        $transaction = Transaction::findOrFail($request->id);
        $transaction->update($request->all());

        return redirect()->route('transactions.index', $transaction->account_id);
    }
}
