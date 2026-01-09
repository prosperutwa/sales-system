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
        Schema::create('biovet_tech_auth', function (Blueprint $table) {
            $table->id('auto_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // FK to biovet_tech_users
            $table->string('username')->unique();
            $table->string('password');
            $table->tinyInteger('status')->default(1); // 1->active, 0->inactive
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // Foreign key constraint
            $table->foreign('user_id')->references('auto_id')->on('biovet_tech_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biovet_tech_auth');
    }
};
