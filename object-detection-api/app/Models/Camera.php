<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $fillable = ['name', 'location'];

    public function sackMovements()
    {
        return $this->hasMany(SackMovement::class);
    }
}
