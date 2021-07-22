<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable =['name','client_id','post_id','type'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
