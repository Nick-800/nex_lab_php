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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name')->unique();
            $table->string('test_description');
            $table->string('test_type');
            $table->float('price');
            $table->integer('duration');
            $table->integer('available_slots');
            $table->string('test_code')->unique();
            $table->string('instructions');
            $table->integer('icon_id');
            $table->string('preparation_required');
            $table->enum('status',["available","not available"]);
            $table->integer('max_bookings_per_slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
