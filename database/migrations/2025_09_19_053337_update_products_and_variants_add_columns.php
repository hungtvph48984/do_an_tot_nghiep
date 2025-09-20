<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->after('name');
            });
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            }

            if (!Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->after('size_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
            if (Schema::hasColumn('product_variants', 'sku')) {
                $table->dropColumn('sku');
            }
        });

        if (Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }
};
