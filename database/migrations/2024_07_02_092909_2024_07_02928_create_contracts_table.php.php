<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guppa_job_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('client_id');
            $table->foreignId('applied_job_id')->constrained();
            $table->integer('progress')->default(0);
            $table->float('total_earnings')->default(0);
            $table->timestamp('total_hours_worked')->nullable();
            $table->enum('status', ['Awaiting Review','In Progress','Done'])->default('In Progress');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
