<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete();
            $table->foreignId('worker_id')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('assigned_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('issue_id');
            $table->index('worker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_assignments');
    }
};
