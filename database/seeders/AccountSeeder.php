<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Tom G',
                'available_balance' => 1000.00,
                'email' => 'tom@gmail.com',
            ],
            [
                'name' => 'Flynn G',
                'available_balance' => 5000.00,
                'email' => 'flynng@gmail.com',
            ],
        ];

        foreach ($accounts as $accountData) {
            $account = Account::create($accountData);

            // Insert related transactions for each account
            Transaction::create([
                'reference' => 'TXN-' . uniqid(),
                'transaction_type_id' => 2,
                'amount' => $account->available_balance,
                'previous_balance' => 0,
                'balance_after' => $account->available_balance,
                'invoice_total_amount' => 500.00,
                'account_id' => $account->id,
            ]);
        }
    }
}
