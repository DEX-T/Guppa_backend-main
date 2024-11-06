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
        Schema::create('freelancer_on_boardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('gigs');
            $table->string('years_of_experience');
            $table->string('looking_for');
            $table->text('skills');
            $table->string('portfolio_link_website');
            $table->string('language');
            $table->text('short_bio');
            $table->string('hourly_rate');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_on_boardings');
    }
};
