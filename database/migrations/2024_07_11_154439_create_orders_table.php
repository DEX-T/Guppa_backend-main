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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->unique();
            $table->enum('type', ['job', 'bid']);
            $table->foreignId('user_id')->constrained();
            $table->float('total_amount')->default(0);
            $table->integer('quantity')->default(0);
            $table->enum('order_status', ['pending','completed', 'canceled'])->default('pending');
            //billing
            $table->string('billing_fullName')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone_number')->nullable();
            $table->string('billing_country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
