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
        Schema::create('testimonial_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->text('testimonials');
            $table->string('portfolio');
            $table->integer('name');
            $table->string('profile_picture');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_cards');
    }
};
