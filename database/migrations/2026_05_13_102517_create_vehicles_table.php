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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->enum('type', ['SUV', 'MPV', 'Sedan', 'Hatchback', 'Minibus']);
        $table->enum('transmission', ['Manual', 'Automatic']);
        $table->string('fuel_type');
        $table->integer('seats');
        $table->integer('luggage_capacity');
        $table->decimal('price_per_day', 12, 2);
        $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
        $table->string('image_url')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
