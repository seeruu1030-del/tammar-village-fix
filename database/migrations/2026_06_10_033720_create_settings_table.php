<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('settings')->insert([
            ['key' => 'security_fee', 'value' => '150000'],
            ['key' => 'waste_fee', 'value' => '50000'],
            ['key' => 'bank_name', 'value' => 'BNI'],
            ['key' => 'bank_account_number', 'value' => '8823 0081 1223 3445'],
            ['key' => 'bank_account_name', 'value' => 'BENDAHARA THE TAMAR VILLAGE'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
