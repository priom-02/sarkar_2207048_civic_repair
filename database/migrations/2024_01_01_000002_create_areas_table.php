<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('division', 100);
            $table->string('district', 100);
            $table->string('upazila', 100);
            $table->string('union_parishad', 100)->nullable();
            $table->string('area_name', 100);
            $table->string('city', 100);
            $table->decimal('latitude_center', 10, 7)->nullable();
            $table->decimal('longitude_center', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
