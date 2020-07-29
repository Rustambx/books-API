<?php

namespace App\Modules\Comment\Models;

use App\Modules\Book\Models\Book;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['description', 'book_id', 'user_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
