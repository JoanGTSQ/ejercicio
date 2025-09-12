<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('exercises', function (Blueprint $table) {
        $table->text('notes')->nullable();
        $table->integer('weight_done')->nullable();
        $table->integer('reps_done')->nullable();
        $table->string('time_done')->nullable();
    });
}

public function down(): void
{
    Schema::table('exercises', function (Blueprint $table) {
        $table->dropColumn(['notes','weight_done','reps_done','time_done','completed']);
    });
}

};
