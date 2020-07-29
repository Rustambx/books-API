<?php

namespace App\Modules\Taxonomy\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use Sortable;

    protected $fillable = [
        'name', 'slug', 'description', 'locale', 'weight'
    ];

    protected $primaryKey = 'id';

    protected $table = 'vocabularies';

    /**
     * Relationships
     */

    public function terms()
    {
        return $this->hasMany(Term::class, 'vocabulary_id', 'id');
    }
}
