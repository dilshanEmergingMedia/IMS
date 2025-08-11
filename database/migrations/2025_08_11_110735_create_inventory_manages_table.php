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
        Schema::create('inventory_manages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('screenLocation_id')->constrained()->onDelete('cascade');
            $table->foreignId('spare_id')->constrained()->onDelete('cascade');
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('remark')->nullable();
            $table->enum('status', ['check-in', 'check-out'])
                ->default('check-in')
                ->comment('check-in, check-out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_manages');
    }
};
