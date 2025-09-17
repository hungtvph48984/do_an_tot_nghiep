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
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('address');
            $table->string('phone');
            $table->string('email');
<<<<<<< HEAD
            $table->string('note')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('payment_method');
=======
            $table->tinyInteger('status');
            $table->string('payment');
             $table->text('note')->nullable();
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
            $table->decimal('total', 10, 2);
            $table->integer('vorcher_code')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->decimal('pay_amount');
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
