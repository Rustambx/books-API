<?php

namespace App\Modules\RBAC\Models;

use App\Modules\RBAC\Contracts\PermissionInterface;
use App\Modules\RBAC\Traits\PermissionTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission model.
 *
 * @package App\Modules\RBAC\Models
 */
class Permission extends Model implements PermissionInterface
{
    use PermissionTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'permissions';
}