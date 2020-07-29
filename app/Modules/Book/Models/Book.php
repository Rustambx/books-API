<?php

namespace App\Modules\Book\Models;

use App\Modules\Comment\Models\Comment;
use App\Modules\Taxonomy\Models\Term;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'language', 'ref_id', 'image'
    ];

    public function getPriceIntAttribute()
    {
        return $this->price / 100;
    }

    public function genres()
    {
        return $this->belongsToMany(Term::class, 'book_genres');
    }

    public function audios()
    {
        return $this->hasMany(Audio::class);
    }

    /*public function getIdAttribute()
    {

        $this->genres;
        $this->authors;


        return $this->attributes['id'];
    }*/

    public function ebooks()
    {
        return $this->hasMany(Ebook::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Term::class, 'book_authors');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    protected static function boot()
    {
        parent::boot();
        /**
         * Удаление картинок и ресайзов
         */
        static::deleted (function ($model) {

            $realPath = storage_path().'/app/public/upload/images/'. $model->image;
            if (file_exists($realPath)) {
                if (preg_match('/(.*?)(\w+)\.(\w+)$/', $model->image, $matches)) {
                    $files = glob(storage_path().'/app/public/upload/images/' . $matches[1] . $matches[2] . '_resize_*');
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            unlink($file);
                        }
                    }
                }
                unlink($realPath);

                if (preg_match('/^(\w+)\//', $model->image, $matches)) {
                    $dir = storage_path().'/app/public/upload/images/' . $matches[1];
                    if (!empty($dir)) {
                        rmdir($dir);
                    }
                }
            }

        });
    }


}
