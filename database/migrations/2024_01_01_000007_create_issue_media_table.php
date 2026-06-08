<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete();
            $table->string('file_url', 500);
            $table->enum('media_type', ['image', 'video']);
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->timestamps();

            $table->index('issue_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_media');
    }
};
