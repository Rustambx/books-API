<?php

namespace App\Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
{
    protected $table = 'book_comments';

    protected $fillable = ['book_id', 'comment_id'];
}
