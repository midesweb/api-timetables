<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'day',
        'start_time',
        'duration',
        'info',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function timetable() : BelongsTo
    {
        return $this->belongsTo(Timetable::class);
    }
}
