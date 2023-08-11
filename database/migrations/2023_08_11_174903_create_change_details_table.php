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
        Schema::create('change_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audience_id')->constrained();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('change_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_details');
    }
};
