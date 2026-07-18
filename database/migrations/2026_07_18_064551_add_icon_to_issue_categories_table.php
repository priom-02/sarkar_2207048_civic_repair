<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('issue_categories', function (Blueprint $table) {
            $table->string('icon', 10)->default('📋')->after('description');
        });

        // Set default icons for existing categories
        $defaults = [
            'Broken Road / Pothole' => '🛣️',
            'Garbage & Waste' => '♻️',
            'Water Leakage / Supply' => '💧',
            'Sewerage & Drainage' => '🚽',
            'Street Lighting' => '💡',
            'Electricity / Power' => '⚡',
            'Traffic & Signals' => '🚦',
            'Tree / Vegetation' => '🌳',
            'Public Property Damage' => '🏛️',
            'Noise & Air Pollution' => '💨',
            'Footpath / Pavement' => '🚶',
            'Other' => '📋',
        ];

        foreach ($defaults as $name => $icon) {
            DB::table('issue_categories')
                ->where('category_name', $name)
                ->update(['icon' => $icon]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
