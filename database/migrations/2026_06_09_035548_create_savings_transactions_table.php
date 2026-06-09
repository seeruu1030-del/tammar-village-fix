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
        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->foreignId('savings_program_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->enum('type', ['deposit', 'withdrawal'])->default('deposit');
            $table->enum('method', ['Cash', 'Transfer'])->default('Cash');
            $table->string('reference_no')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('completed');
            $table->text('note')->nullable();
            $table->string('proof_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_transactions');
    }
};
