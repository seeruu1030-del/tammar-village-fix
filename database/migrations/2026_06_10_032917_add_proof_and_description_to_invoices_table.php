<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('description')->nullable()->after('period');
            $table->string('proof_path')->nullable()->after('status');
            $table->timestamp('payment_date')->nullable()->after('proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['description', 'proof_path', 'payment_date']);
        });
    }
};
