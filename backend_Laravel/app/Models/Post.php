<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'image',
        'category',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function favoriteByUser(){
        return $this->belongsToMany(User::class, 'favorites');
    }
}
