<?php

namespace App\Modules\RBAC\Traits;

trait PermissionTrait
{
    /**
     * Many-to-Many relationship.
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(\App\Modules\RBAC\Models\Role::class, 'permission_role');
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($permission) {
            if (!method_exists(\App\Modules\RBAC\Models\Permission::class, 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }

            return true;
        });
    }
}