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
        Schema::create('freelancer_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')->constrained('users');
            $table->foreignId('rated_by')->constrained('users'); // The user who rates the freelancer
            $table->tinyInteger('rating')->unsigned(); // Assuming rating is between 1 and 5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_ratings');
    }
};
