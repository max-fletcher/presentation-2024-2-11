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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('remaining_balance', 10, 4, true);
            $table->string('dummy_string1');
            $table->string('dummy_string2');
            $table->string('dummy_string3');
            $table->string('dummy_string4');
            $table->string('dummy_string5');
            $table->string('dummy_string6');
            $table->string('dummy_string7');
            $table->string('dummy_string8');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
