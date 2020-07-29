<?php

namespace App\Modules\RBAC\Traits;

use Cache;
use Config;
use Illuminate\Cache\TaggableStore;

trait RoleTrait
{
    /**
     * Attaches a permission to the role.
     *
     * @param array|\App\Modules\RBAC\Models\Permission $permission
     *
     * @return mixed
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            return $this->attachPermissions($permission);
        }

        $this->perms()->attach($permission);

        $this->updatePermissionsCache();
    }

    /**
     * Attaches multiple permissions to the role.
     *
     * @param array $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($role) {
            if (!method_exists(\App\Modules\RBAC\Models\Role::class, 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }

            return true;
        });
    }

    /**
     * Caches the role's permissions.
     *
     * @return mixed
     */
    public function cachedPermissions()
    {
        $cacheKey = 'rbac_permissions_for_role_'.$this->getKey();

        if ($this->cacheIsTaggable()) {
            return Cache::tags('permission_role')->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->perms()->get();
            });
        }

        return Cache::remember($cacheKey, 60, function () {
            return $this->perms()->get();
        });
    }

    /**
     * Check whether the cache storage does support tagging.
     *
     * @return bool
     */
    protected function cacheIsTaggable()
    {
        return Cache::getStore() instanceof TaggableStore;
    }

    /**
     * Deletes the model.
     *
     * @param array|null $options
     *
     * @return bool
     */
    public function delete($options = null)
    {
        if(!parent::delete($options)){
            return false;
        }

        $this->flushCache();

        return true;
    }

    /**
     * Detaches a permission from the role.
     *
     * @param array|\App\Modules\RBAC\Models\Permission $permission
     *
     * @return mixed
     */
    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            return $this->detachPermissions($permission);
        }

        $this->perms()->detach($permission);

        $this->updatePermissionsCache();
    }

    /**
     * Detaches multiple permissions from the role.
     *
     * @param array|null $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions = null)
    {
        if (!$permissions) $permissions = $this->perms()->get();

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * Flushes the cache.
     *
     * @return void
     */
    protected function flushCache()
    {
        $cacheKey = 'rbac_permissions_for_role_'.$this->getKey();

        if ($this->cacheIsTaggable()) {
            Cache::tags('permission_role')->flush();
        } else {
            Cache::forget($cacheKey);
        }
    }

    /**
     * Checks if the role has specified permission.
     *
     * @param string|array  $name
     * @param bool          $requireAll
     *
     * @return bool
     */
    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            foreach ($this->cachedPermissions() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Many-to-Many relationship.
     *
     * @return mixed
     */
    public function perms()
    {
        return $this->belongsToMany(
            \App\Modules\RBAC\Models\Permission::class,
            'permission_role',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Restores soft-deleted models.
     *
     * @return bool
     */
    public function restore()
    {
        if(!parent::restore()){
            return false;
        }

        $this->flushCache();

        return true;
    }

    /**
     * Saves the model.
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if(!parent::save($options)){
            return false;
        }

        $this->flushCache();

        return true;
    }

    /**
     * Saves permissions.
     *
     * @param $inputPermissions
     */
    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->perms()->sync($inputPermissions);
        } else {
            $this->perms()->detach();
        }

        $this->flushCache();
    }

    /**
     * Updates permissions cache.
     *
     * @return void
     */
    public function updatePermissionsCache()
    {
        $this->flushCache();

        $this->cachedPermissions();
    }
}