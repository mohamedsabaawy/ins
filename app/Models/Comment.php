<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Comment extends Pivot
{
    protected $fillable = ['client_id' , 'post_id','comment','created_at','updated_at'];
}
