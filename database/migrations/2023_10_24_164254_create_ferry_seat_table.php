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
        Schema::create('ferry_seat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_id');
            $table->foreignId('ferry_id');
            $table->string('status')->default('empty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_seat');
    }
};
