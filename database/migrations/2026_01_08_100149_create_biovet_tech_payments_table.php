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
        Schema::create('biovet_tech_payments', function (Blueprint $table) {
            $table->id('auto_id'); // Primary key
            $table->unsignedBigInteger('invoice_id'); // FK to invoices
            $table->decimal('amount_paid', 12, 2);
            $table->enum('payment_method', ['cash', 'mobile', 'bank'])->default('cash');
            $table->date('payment_date');
            $table->string('reference_number')->nullable();
            $table->timestamps(); // created_at, updated_at

            // Foreign key constraint
            $table->foreign('invoice_id')->references('auto_id')->on('biovet_tech_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_payments');
    }
};
