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
        Schema::create('apero_tractor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apero_id')->constrained()->onDelete('cascade');
            $table->foreignId('tractor_id')->constrained()->onDelete('cascade');
            $table->timestamp('attached_at')->nullable();
            $table->timestamp('detached_at')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apero_tractor');
    }
};
