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
        Schema::create('guppa_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('guppa_job_id')->constrained();
            $table->string('tnx_ref');
            $table->float('amount')->default(0.0);
            $table->enum('type', ['income', 'withdrawal']);
            $table->enum('status', ['completed', 'processing'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guppa_transactions');
    }
};
