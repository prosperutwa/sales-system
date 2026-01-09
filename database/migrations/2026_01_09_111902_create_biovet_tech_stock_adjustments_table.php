<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biovet_tech_stock_adjustments', function (Blueprint $table) {
            $table->bigIncrements('auto_id');

            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');

            $table->timestamps();

            // Foreign Key
            $table->foreign('product_id')
                  ->references('auto_id')
                  ->on('biovet_tech_products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_stock_adjustments');
    }
};
