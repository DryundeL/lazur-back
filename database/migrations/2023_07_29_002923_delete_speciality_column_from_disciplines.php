<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->dropColumn('speciality_id');
        });
    }

    public function down(): void
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->foreignId('speciality_id')->nullable()->constrained();
        });
    }
};
