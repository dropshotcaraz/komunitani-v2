<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image_path', // Add image path
        'topic'       // Add topic
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function shares()
    {
        return $this->hasMany(Share::class);
    }

        // Search Scope
    public function scopeSearch(Builder $query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('content', 'LIKE', "%{$searchTerm}%")
              ->orWhere('topic', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
