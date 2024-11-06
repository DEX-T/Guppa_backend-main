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
        Schema::table('guppa_jobs', function (Blueprint $table) {
            $table->timestamp('time_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guppa_jobs', function (Blueprint $table) {
            $table->dropColumn('time_limit');
        });
    }
};