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
        Schema::create('biovet_tech_products', function (Blueprint $table) {
            $table->id('auto_id'); // primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('buying_price', 12, 2); // e.g., 9999999999.99 max
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock_quantinty')->default(0);
            $table->integer('remain_quantity')->default(0);
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_products');
    }
};
