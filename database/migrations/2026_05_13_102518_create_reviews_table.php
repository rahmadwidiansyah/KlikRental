<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Kolom Rating (Skala 1-5)
            $table->integer('vehicle_rating');
            $table->integer('company_rating');
            $table->integer('driver_rating')->nullable(); // Nullable karena bisa lepas kunci
            
            $table->text('comment')->nullable(); // Ulasan teks opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};