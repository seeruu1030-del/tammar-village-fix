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
        Schema::create('savings_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_id')->unique(); // e.g., PROG-01
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->enum('status', ['active', 'locked', 'completed'])->default('active');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_programs');
    }
};
