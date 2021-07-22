<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LikePost extends Pivot
{
    protected $fillable = ['client_id' , 'post_id'];
}
