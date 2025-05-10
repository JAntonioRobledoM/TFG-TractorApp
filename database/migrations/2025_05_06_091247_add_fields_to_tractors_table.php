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
        Schema::table('tractors', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->string('license_plate')->unique()->nullable();
            $table->string('color')->nullable();
            $table->integer('horsepower')->nullable();
            $table->decimal('working_hours', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tractors', function (Blueprint $table) {
            //
        });
    }
};
