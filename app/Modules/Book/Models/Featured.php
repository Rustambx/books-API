<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Featured extends Model
{
    protected $table = 'featured_books';

    protected $fillable = ['book_id', 'user_id'];
}
