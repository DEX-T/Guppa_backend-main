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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->unique();
            $table->boolean('email_notifications')->default(false);
            $table->boolean('push_notifications')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('in_app_notifications')->default(false);
            $table->boolean('profile_visibility')->default(false);
            $table->boolean('search_visibility')->default(false);
            $table->boolean('data_sharing')->default(false);
            $table->boolean('location_settings')->default(false);
            $table->boolean('ad_preferences')->default(false);
            $table->boolean('activity_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
