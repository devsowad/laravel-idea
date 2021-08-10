<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function deleteVote(User $user)
    {
        try {
            return Vote::where('user_id', $user->id)
                ->where('idea_id', $this->id)
                ->delete();
        } catch (Exception) {
            //
        }
    }

    public function vote(User $user)
    {
        try {
            if (!$this->deleteVote($user)) {
                $vote = Vote::create([
                    'user_id' => $user->id,
                    'idea_id' => $this->id,
                ]);
                return $vote->id;
            } else {
                return false;
            }
        } catch (Exception) {
            //
        }
    }
}
