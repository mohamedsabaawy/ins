<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $fillable = ['name','client_id','post_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Client::class ,'like_posts')->withTimestamps();
    }

    public function comments()
    {
        return $this->belongsToMany(Client::class ,'comments')->withPivot([
            'comment','created_at','id'
        ])->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function share()
    {
        return $this->belongsTo(Post::class,'post_id');
    }

    public function master()
    {
        return $this->hasMany(Post::class,'post_id');
    }



    public function getIsLikeAttribute()
    {

        $like = Auth::guard('client')->user()->whereHas('likes',function ($query){
            $query->where('like_posts.post_id',$this->id);
        })->first();
        if ($like){
            return true;
        }
        return false;
    }
}
