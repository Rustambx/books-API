<?php

namespace App\Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    protected $table = 'user_comments';

    protected $fillable = ['user_id', 'comment_id'];
}
