<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->text('body');
            $table->boolean('is_internal')->default(false)
                  ->comment('Admin-only notes not visible to citizens');
            $table->timestamps();

            $table->index('issue_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_comments');
    }
};
