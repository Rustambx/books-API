<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'book_genres';

    protected $fillable = ['term_id', 'book_id'];


}
