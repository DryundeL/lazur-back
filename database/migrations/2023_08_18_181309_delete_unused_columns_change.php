<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('changes', function (Blueprint $table) {
            $table->dropColumn('week_type');
            $table->dropColumn('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::table('changes', function (Blueprint $table) {
            $table->string('week_type');
            $table->string('day_of_week');
        });
    }
};
