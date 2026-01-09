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
        Schema::create('biovet_tech_company_settings', function (Blueprint $table) {
            $table->id('auto_id'); // Primary key
            $table->string('company_name', 255);
            $table->text('company_address')->nullable();
            $table->string('company_phone', 50)->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('company_logo', 255)->nullable();
            $table->string('company_tin', 50)->nullable();
            $table->string('default_currency', 10)->default('TZS');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->text('invoice_footer_note')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_company_settings');
    }
};
