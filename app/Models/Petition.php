<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id', 'title', 'description', 'category',
        'visibility', 'status', 'threshold', 'upvote_count', 'image_path'
    ];

    protected $casts = [
        'threshold' => 'integer',
        'upvote_count' => 'integer',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function votes()
    {
        return $this->hasMany(PetitionVote::class);
    }

    public function threads()
    {
        return $this->hasMany(PetitionThread::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Helper methods
    public function hasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function incrementVotes()
    {
        $this->increment('upvote_count');
    }

    public function decrementVotes()
    {
        $this->decrement('upvote_count');
    }

    public function hasReachedThreshold()
    {
        return $this->upvote_count >= $this->threshold;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }
}
