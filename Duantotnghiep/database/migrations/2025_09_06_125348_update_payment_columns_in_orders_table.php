<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nếu cột payment đang tồn tại thì đổi tên
            if (Schema::hasColumn('orders', 'payment')) {
                $table->renameColumn('payment', 'payment_method');
            }

            // Đảm bảo payment_method có enum rõ ràng
            $table->enum('payment_method', ['cod', 'online'])
                  ->default('cod')
                  ->change();

            // Nếu chưa có payment_status thì thêm
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'paid', 'failed'])
                      ->default('unpaid')
                      ->after('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback về cột payment (string)
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->renameColumn('payment_method', 'payment');
                $table->string('payment')->nullable()->change();
            }

            // Xoá cột payment_status
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
