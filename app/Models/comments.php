<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'id', 'updated_at', 'post_id'
    ];
}
