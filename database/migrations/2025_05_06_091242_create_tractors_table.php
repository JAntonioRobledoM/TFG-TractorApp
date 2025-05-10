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
        Schema::create('tractors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('field_2')->nullable(); // ¿posiblemente se puede renombrar luego?
            $table->string('model')->nullable();
            $table->integer('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tractors');
    }
};
