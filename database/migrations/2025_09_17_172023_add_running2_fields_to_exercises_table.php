<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            #$table->integer('distance')->nullable();      // metros
            $table->integer('bpm_min')->nullable();       // frecuencia mínima
            $table->integer('bpm_max')->nullable();       // frecuencia máxima
            $table->integer('rest_min')->nullable();      // descanso mínimo (segundos)
            $table->integer('rest_max')->nullable();      // descanso máximo (segundos)
            $table->integer('intensity_min')->nullable(); // % esfuerzo mínimo
            $table->integer('intensity_max')->nullable(); // % esfuerzo máximo
        });
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'distance',
                'bpm_min',
                'bpm_max',
                'rest_min',
                'rest_max',
                'intensity_min',
                'intensity_max',
            ]);
        });
    }
};
