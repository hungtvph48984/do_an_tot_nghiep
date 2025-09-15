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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment primary key
            $table->string('name'); // varchar(255), not null
            $table->string('email'); // varchar(255), not null
            $table->string('phone')->nullable(); // varchar(255), null
            $table->text('message'); // text, not null
            $table->timestamps(); // created_at & updated_at (nullable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
