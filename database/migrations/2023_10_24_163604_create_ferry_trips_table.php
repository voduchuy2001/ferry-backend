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
        Schema::create('ferry_trips', function (Blueprint $table) {
            $table->id();
            $table->date('departure_date');
            $table->time('departure_time');
            $table->foreignId('ferry_id');
            $table->foreignId('ferry_route_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_trips');
    }
};
