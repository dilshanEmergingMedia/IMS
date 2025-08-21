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
        Schema::create('inventory_manage_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_manage_id')
                ->constrained('inventory_manages')
                ->onDelete('cascade');
            $table->foreignId('spare_id')
                ->constrained('spares')
                ->onDelete('cascade');
            $table->string('model')->nullable();
            $table->string('quantity')->nullable();
            $table->string('condition')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_manage_details');
    }
};
