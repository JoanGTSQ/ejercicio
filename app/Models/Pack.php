<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model {
    protected $fillable = ['name','start_date','end_date','is_active','weeks'];

    public function days() {
        return $this->hasMany(Day::class);
    }
}