<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('age');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('email')->nullable()->after('contact');
            $table->string('telegram_id')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'birth_date', 'email', 'telegram_id']);
        });
    }
};
