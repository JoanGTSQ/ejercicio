<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiitExercise extends Model {
    protected $fillable = ['exercise_id','name','duration','reps'];

    public function exercise() {
        return $this->belongsTo(Exercise::class);
    }
}