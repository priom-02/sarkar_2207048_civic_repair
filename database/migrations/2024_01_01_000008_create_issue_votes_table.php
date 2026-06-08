<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();

            // One vote per user per issue
            $table->unique(['issue_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_votes');
    }
};
