<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_times', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->time('time_start');
            $table->time('time_end');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_times');
    }
};
