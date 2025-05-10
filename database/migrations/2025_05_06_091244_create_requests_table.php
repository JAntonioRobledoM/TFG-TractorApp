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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->string('type', 10);
            $table->string('status', 20);
            $table->decimal('offered_price', 10, 2)->nullable();
            $table->date('requested_start_date')->nullable();
            $table->date('requested_end_date')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
