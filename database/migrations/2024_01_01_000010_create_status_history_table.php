<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete();
            $table->foreignId('old_status_id')
                  ->nullable()
                  ->constrained('issue_statuses')
                  ->restrictOnDelete();
            $table->foreignId('new_status_id')
                  ->constrained('issue_statuses')
                  ->restrictOnDelete();
            $table->foreignId('changed_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->index('issue_id');
            $table->index('changed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_history');
    }
};
