<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guppa_jobs', function (Blueprint $table) {
            $table->integer('views')->default(0);
            $table->integer('applications')->default(0);
            $table->float('relevance_score')->default(0);
        });
    }

    public function down()
    {
        Schema::table('guppa_jobs', function (Blueprint $table) {
            $table->dropColumn(['views', 'applications', 'relevance_score']);
        });
    }
};
