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
            $table->tinyInteger('status');
            $table->string('payment_method'); // cod, bank
$table->string('payment_status')->default('unpaid'); // unpaid, paid
             $table->text('note')->nullable();
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
