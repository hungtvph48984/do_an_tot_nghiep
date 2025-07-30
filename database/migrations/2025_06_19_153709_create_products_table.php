<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category'); // ID của danh mục
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->json('listing')->nullable(); // Hiện là [], nên dùng kiểu json
            $table->bigInteger('price')->default(0); // Giá sản phẩm
            $table->text('description')->nullable(); // Có chứa HTML như <p>
            $table->timestamp('created_at')->nullable(); // Thời gian tạo
            $table->timestamp('updated_at')->nullable(); // Thời gian cập nhật
            $table->boolean('status')->default(1); // 1 = hiển thị, 0 = ẩn
            $table->integer('amount')->default(0); // Số lượng tồn kho
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
