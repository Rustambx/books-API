<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audios';

    protected $fillable = ['file', 'book_id'];
}
