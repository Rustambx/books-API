<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'book_authors';

    protected $fillable = ['term_id', 'book_id'];
}
