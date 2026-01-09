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
        Schema::create('biovet_tech_invoices', function (Blueprint $table) {
            $table->id('auto_id'); // Primary key
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->date('invoice_date');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['paid', 'unpaid', 'cancelled'])->default('unpaid');
            $table->timestamps(); // created_at, updated_at

            // Foreign key constraints
            $table->foreign('customer_id')->references('auto_id')->on('biovet_tech_customers')->onDelete('cascade');
            $table->foreign('user_id')->references('auto_id')->on('biovet_tech_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_invoices');
    }
};
