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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->string('name', 64);
            $table->bigInteger('value');
            $table->enum('status', ['AWAITING_PAYMENT', 'PAID', 'EXPIRED']);

            $table->date('due_date')->nullable();
            $table->string('category', 32)->nullable();
            $table->string('payment_method', 32)->nullable();
            $table->string('payment_source', 32)->nullable();

            $table->boolean('recurrent')->default(false);
            $table->boolean('auto_pay')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
