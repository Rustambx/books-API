<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $table = 'ebooks';

    protected $fillable = ['file', 'book_id'];
}
