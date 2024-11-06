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
            $table->string('required_skills')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guppa_jobs', function (Blueprint $table) {
            $table->dropColumn('required_skills');
        });
    }
};