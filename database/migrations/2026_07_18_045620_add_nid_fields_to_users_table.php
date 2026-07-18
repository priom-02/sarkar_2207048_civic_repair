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
            $table->string('nid_number', 20)->nullable()->after('phone');
            $table->string('nid_front_photo')->nullable()->after('nid_number');
            $table->string('nid_back_photo')->nullable()->after('nid_front_photo');
            $table->enum('nid_verified', ['pending', 'verified', 'rejected'])->default('pending')->after('nid_back_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nid_number', 'nid_front_photo', 'nid_back_photo', 'nid_verified']);
        });
    }
};
