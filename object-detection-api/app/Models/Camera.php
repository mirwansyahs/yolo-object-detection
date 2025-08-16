<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Camera extends Model
{
    protected $fillable = ['name', 'location'];

    public function sackMovements()
    {
        return $this->hasMany(SackMovement::class);
    }
    
    public function getCreatedAtLabelAttribute()
    {
        return optional($this->created_at)->translatedFormat('l, d M Y H:i:s'); 
    }

}
