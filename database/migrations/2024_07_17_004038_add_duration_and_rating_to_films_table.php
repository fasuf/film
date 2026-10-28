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
        Schema::table('films', function (Blueprint $table) {
            if (!Schema::hasColumn('films', 'duration')) {
                $table->integer('duration')->nullable()->after('year'); // in minutes
            }
            if (!Schema::hasColumn('films', 'rating')) {
                $table->decimal('rating', 3, 1)->nullable()->after('duration'); // e.g. 8.5
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            if (Schema::hasColumn('films', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('films', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
};
