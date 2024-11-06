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
        Schema::create('guppa_jobs', function (Blueprint $table) {
            $table->id();
            $table->ForeignId('user_id')->constrained();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->text('tags')->nullable();
            $table->double('amount');
            $table->string('time');
            $table->integer('bid_points');
            $table->enum('project_type', ['hourly', 'contract']);
            $table->enum('job_status', ['available', 'taken'])->default('available');
            $table->enum('visibility', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
