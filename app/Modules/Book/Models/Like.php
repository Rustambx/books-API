<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'comment_likes';

    protected $fillable = ['comment_id', 'user_id'];
}
