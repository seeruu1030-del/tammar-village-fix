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
        Schema::table('savings_programs', function (Blueprint $table) {
            if (Schema::hasColumn('savings_programs', 'max_participants')) {
                $table->dropColumn('max_participants');
            }
        });

        Schema::table('savings_transactions', function (Blueprint $table) {
            // Change status to string for flexibility
            $table->string('status')->default('pending')->change();
            
            $table->string('payment_method')->default('manual')->after('amount');
            $table->string('midtrans_order_id')->nullable()->after('payment_method');
            $table->string('midtrans_snap_token')->nullable()->after('midtrans_order_id');
        });
        
        // Update existing 'completed' status to 'success' for consistency if desired
        \DB::table('savings_transactions')->where('status', 'completed')->update(['status' => 'success']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_programs', function (Blueprint $table) {
            $table->integer('max_participants')->default(0)->after('target_amount');
        });

        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'midtrans_order_id', 'midtrans_snap_token']);
            // We can't easily revert the 'status' column type back to enum and restore 'completed'
            // but we can at least try to set it back to a limited set if needed.
        });
    }
};
