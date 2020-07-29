<?php

namespace App\Modules\RBAC\Models;

use App\Modules\RBAC\Contracts\RoleInterface;
use App\Modules\RBAC\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Role model.
 *
 * @package App\Modules\RBAC\Models
 */
class Role extends Model implements RoleInterface
{
    use RoleTrait;

    protected $fillable = [
        'name', 'description'
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'roles';
}
