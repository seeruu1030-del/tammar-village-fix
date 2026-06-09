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
            $table->integer('max_participants')->default(0)->after('target_amount'); // 0 = unlimited
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_programs', function (Blueprint $table) {
            $table->dropColumn('max_participants');
        });
    }
};
