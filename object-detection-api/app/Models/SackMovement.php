<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class SackMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'camera_id',
        'user_id',
        'direction',
        'detected_at',
        'sack_count',
        'image_path',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    // Relasi ke Camera
    public function camera()
    {
        return $this->belongsTo(Camera::class);
    }
    
    public function getDirectionLabelAttribute()
    {
        return $this->direction === 'in' ? 'Karung Masuk' : 'Karung Keluar';
    }

    
    public function getDetectedAtLabelAttribute()
    {
        return optional($this->detected_at)->translatedFormat('l, d M Y H:i:s'); 
    }

    // Relasi ke User (jika digunakan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
