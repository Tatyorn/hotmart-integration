<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('id')->index();
            $table->foreignId('user_id')->constrained('users');
            $table->string('status');
            $table->dateTime('purchase_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->dateTime('cancellation_date')->nullable();
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
