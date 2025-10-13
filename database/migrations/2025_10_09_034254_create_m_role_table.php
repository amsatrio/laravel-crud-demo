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
        Schema::create('m_role', function (Blueprint $table) {
            $table->id('id'); // Primary key, bigint unsigned
            $table->string('name', 20)->nullable();
            $table->string('code', 20)->nullable();
            $table->tinyInteger('level')->nullable(); // tinyint

            // Audit columns based on your Go structure (assuming unsigned bigints for users/ID)
            $table->unsignedBigInteger('created_by')->nullable(); // uint
            $table->dateTime('created_on')->nullable();        // JSONTime, use dateTime
            $table->unsignedBigInteger('modified_by')->nullable(); // *uint
            $table->dateTime('modified_on')->nullable();       // *JSONTime
            
            // Soft Delete columns
            $table->unsignedBigInteger('deleted_by')->nullable(); // *uint
            $table->dateTime('deleted_on')->nullable();       // *JSONTime
            $table->boolean('is_delete')->default(false);        // *bool
            
            // **Note:** If you want to use Laravel's built-in `timestamps()` and `softDeletes()`,
            // use those helpers instead, and you will need to adjust the Model accordingly.
            // E.g., $table->timestamps(); $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_role');
        Schema::dropIfExists('m_roles');
    }
};
