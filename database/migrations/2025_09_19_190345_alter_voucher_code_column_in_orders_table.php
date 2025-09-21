<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // đổi int -> string và cho phép null
            $table->string('voucher_code', 50)->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('voucher_code')->nullable()->change();
        });
    }
};
