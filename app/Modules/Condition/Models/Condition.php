<?php

namespace App\Modules\Condition\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $table = 'terms_conditions';

    protected $fillable = ['title', 'text'];
}
