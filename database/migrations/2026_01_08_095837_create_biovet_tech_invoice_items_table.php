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
        Schema::create('biovet_tech_invoice_items', function (Blueprint $table) {
            $table->id('auto_id'); // Primary key
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps(); // created_at, updated_at

            // Foreign key constraints
            $table->foreign('invoice_id')->references('auto_id')->on('biovet_tech_invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('auto_id')->on('biovet_tech_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_invoice_items');
    }
};
