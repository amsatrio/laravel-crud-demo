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
        Schema::create('m_menu', function (Blueprint $table) {
            $table->id();

            $table->string('name', 64)->nullable();
            $table->string('actions', 64)->nullable();
            $table->text('code')->nullable();

            // Audit columns based on your Go structure (assuming unsigned bigints for users/ID)
            $table->unsignedBigInteger('created_by')->nullable(); // uint
            $table->dateTime('created_on')->nullable();        // JSONTime, use dateTime
            $table->unsignedBigInteger('modified_by')->nullable(); // *uint
            $table->dateTime('modified_on')->nullable();       // *JSONTime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_menu');
    }
};
