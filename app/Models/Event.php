<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'start',
        'end',
        'backgroundColor',
        'borderColor',
        'allDay',
        'category',
        'description',
        'calendar_id',
        'user_id',
    ];
}
