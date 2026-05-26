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
        Schema::table('users', function (Blueprint $table) {
            // Hapus 2 kolom lama yang tidak jadi kita pakai
            $table->dropColumn(['google_avatar', 'profile_picture']);
            
            // Tambahkan kolom 'avatar' yang sesuai dengan Model & Controller
            $table->string('avatar')->nullable()->after('google_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->string('google_avatar')->nullable();
            $table->string('profile_picture')->nullable();
        });
    }
};