<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model {
    protected $fillable = ['pack_id','day_number'];

    public function exercises() {
        return $this->hasMany(Exercise::class);
    }
}