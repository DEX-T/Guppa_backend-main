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
        Schema::create('applied_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guppa_job_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('bid_point');
            $table->float('service_charge');
            $table->float('total_amount_payable');
            $table->timestamp('project_timeline');
            $table->string('cover_letter_file');
            $table->longText('cover_letter');
            $table->enum('payment_type', ['project', 'milestone']);
            $table->float('project_price')->nullable();
            $table->float('total_milestone_price')->nullable();
            $table->enum('status', ['awaiting','approved','rejected'])->default('awaiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_jobs');
    }
};
