<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'image',
        'category_id',
        'user_id',
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->image) {
                $path = str_replace('storage/', '', $post->image);
                Storage::disk('public')->delete($path);
            }
        });
    }

    // Relationships
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function favoriteByUser(){
        return $this->belongsToMany(User::class, 'favorites');
    }
}
