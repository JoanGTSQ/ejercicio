<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model {
    protected $fillable = [
    'day_id','name','series','reps','weight','type',
    'completed','notes','weight_done','reps_done','time_done',
    'distance','pace',
    'bpm_min','bpm_max','rest_min','rest_max',
];

    public function hiitExercises() {
        return $this->hasMany(HiitExercise::class);
    }
}
