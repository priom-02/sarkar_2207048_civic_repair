<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description');
            $table->foreignId('reported_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('category_id')
                  ->constrained('issue_categories')
                  ->restrictOnDelete();
            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->restrictOnDelete();
            $table->foreignId('status_id')
                  ->constrained('issue_statuses')
                  ->restrictOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('upvote_count')->default(0);
            $table->timestamps();

            // Indexes for common query patterns
            $table->index('reported_by');
            $table->index('area_id');
            $table->index('status_id');
            $table->index('category_id');
            $table->index('upvote_count');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
