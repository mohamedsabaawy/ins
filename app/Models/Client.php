<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'hotel';
    protected $fillable =['name','password','email','avatar'];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class ,'like_posts')->withTimestamps();
    }

    public function comments()
    {
        return $this->belongsToMany(Post::class ,'comments')->withTimestamps();
    }

//--------------user follows------------------//
    public function follows()
    {
        return $this->belongsToMany(Client::class ,'follows','follow_id','follower_id')->withTimestamps();
    }

//--------------user followers------------------//
    public function followers()
    {
        return $this->belongsToMany(Client::class ,'follows','follower_id','follow_id')->withTimestamps();
    }
}
