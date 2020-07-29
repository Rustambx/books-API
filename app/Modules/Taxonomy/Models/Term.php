<?php

namespace App\Modules\Taxonomy\Models;

use App\Modules\Book\Models\Book;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Term extends Model
{
    use NodeTrait, Sortable;

    protected $fillable = [
        'vocabulary_id', 'name', 'description', 'icon', 'weight', '_lft', '_rgt', 'parent_id'
    ];

    protected $primaryKey = 'id';

    protected $table = 'terms';

    /*
     * Scopes
     */

    public function scopeFromVocabulary($query, $id)
    {
        return $query->where('vocabulary_id', $id);
    }

    /*
     * Relations
     */

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class, 'vocabulary_id', 'id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_genres');
    }
}
