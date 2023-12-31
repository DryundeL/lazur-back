<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discipline_speciality', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained();
            $table->foreignId('speciality_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discipline_speciality');
    }
};
