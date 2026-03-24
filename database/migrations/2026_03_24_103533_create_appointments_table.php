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
    Schema::create('appointments', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('patient_name');
        $table->string('email');
        $table->date('appointment_date');
        $table->time('appointment_time');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
