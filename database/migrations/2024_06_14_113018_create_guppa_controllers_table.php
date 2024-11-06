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
        Schema::create('guppa_controllers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prefix_id')->constrained();
            $table->foreignId('general_middleware_id')->constrained();
            $table->string('controller');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guppa_controllers');
    }
};
