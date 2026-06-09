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
        Schema::table('residents', function (Blueprint $table) {
            $table->date('exit_date')->nullable()->after('status');
            $table->string('exit_status')->nullable()->after('exit_date');
            $table->text('exit_reason')->nullable()->after('exit_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['exit_date', 'exit_status', 'exit_reason']);
        });
    }
};
