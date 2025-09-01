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
        Schema::create('event_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_inventory_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('model')->nullable();
            $table->enum('condition', ['working', 'faulty', 'need_to_repair', 'repairing']);
            $table->integer('qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_inventory_details');
    }
};
