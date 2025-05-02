<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->integer('transaction_type_id')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->decimal('previous_balance', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->decimal('invoice_total_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
