<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('nik')->unique();
            $table->enum('family_status', ['KK', 'Istri', 'Anak', 'Lainnya'])->default('KK');
            $table->integer('age')->nullable();
            $table->string('contact')->nullable();
            $table->foreignId('block_id')->constrained();
            $table->string('unit_no');
            $table->enum('housing_status', ['Owner', 'Tenant'])->default('Owner');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
