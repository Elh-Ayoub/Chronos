<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sharing extends Model
{
    use HasFactory;
    protected $fillable = [
        'shared_by',
        'target',
        'target_id',
        'shared_to_email',
        'accepted',
    ];
}
