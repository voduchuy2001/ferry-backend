<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('identity');
            $table->string('name');
            $table->string('date_of_birth');
            $table->string('place_of_birth');
            $table->string('nationality');
            $table->string('sex');
            $table->string('email');
            $table->string('address');
            $table->foreignId('seat_id');
            $table->foreignId('ferry_trip_id');
            $table->foreignId('ferry_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
