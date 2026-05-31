<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            // Tambah field baru untuk keperluan kantor cabang
            $table->boolean('is_office')->default(false)->after('is_active');
            $table->text('address')->nullable()->after('is_office');
            $table->text('maps_link')->nullable()->after('address'); // Link embed Gmaps
        });
    }

    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn(['is_office', 'address', 'maps_link']);
        });
    }
};