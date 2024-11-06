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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('visited_route')->nullable();
            $table->string('controller_method')->nullable();
            $table->text("request_headers")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('visited_route', 'controller_method', "request_headers");
//            $table->dropColumn('controller_method');
//            $table->dropColumn("request_headers");
        });
    }
};
