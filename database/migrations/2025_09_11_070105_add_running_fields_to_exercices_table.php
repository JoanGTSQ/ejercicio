<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('exercises', function (Blueprint $table) {
            $table->integer('distance')->nullable()->after('reps');
            $table->string('pace')->nullable()->after('distance');
        });
    }

    public function down(): void {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn(['distance','pace']);
        });
    }
};