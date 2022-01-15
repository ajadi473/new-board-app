<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'creation_date', 
                        'author_name', 'link'];

    protected $hidden = [
        'created_at','id','updated_at'
    ];

    public function comment(){
        return $this->hasMany(comments::class, 'post_id', 'id');
    }
}
