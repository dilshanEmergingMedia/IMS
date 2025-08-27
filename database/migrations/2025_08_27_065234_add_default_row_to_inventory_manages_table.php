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
        Schema::table('inventory_manages', function (Blueprint $table) {
            $table->string('screen')->nullable()->after('store_id');
            $table->string('asset_id')->nullable()->after('screenLocation_id');
            $table->string('asset_models_id')->nullable()->after('asset_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_manages', function (Blueprint $table) {
            //
        });
    }
};
