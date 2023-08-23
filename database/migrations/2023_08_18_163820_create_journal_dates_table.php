<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journal_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('discipline_id')->constrained();
            $table->date('date');
            $table->integer('count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_dates');
    }
};
