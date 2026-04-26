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

        \DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'Barber CRM', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'require_password', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_password', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'open_time', 'value' => '08:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'close_time', 'value' => '20:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};