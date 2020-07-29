<?php

namespace App\Modules\RBAC\Traits;

use Cache;
use Config;
use Illuminate\Cache\TaggableStore;
use InvalidArgumentException;
use RBAC;
use Str;

trait UserTrait
{
    /**
     * Attaches a role to the user.
     *
     * @param integer|array|string|\App\Modules\RBAC\Models\Role $role
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_string($role)) {
            $role = RBAC::findRole($role);

            if (!$role) {
                return false;
            }
        }

        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if (!method_exists(\App\Modules\RBAC\Models\User::class, 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }

            return true;
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
     * Check if the user has one or more specified permissions.
     * If $requireAll is set to true, user mast have all the permissions.
     *
     * @param string|array  $permission
     * @param bool          $requireAll
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            foreach ($this->checkRoles() as $role) {
                foreach ($role->cachedPermissions() as $perm) {
                    if (Str::is( $permission, $perm->name) ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Caches current user's roles.
     *
     * @return mixed
     */
    public function checkRoles()
    {
        $cacheKey = 'rbac_roles_for_user_'.$this->getKey();

        if ($this->cacheIsTaggable()) {
            return Cache::tags('role_user')->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->roles()->get();
            });
        }

        return Cache::remember($cacheKey, 60, function () {
            return $this->roles()->get();
        });
    }

    /**
     * Deletes the model.
     *
     * @param array|null $options
     *
     * @return void
     */
    public function delete($options = null)
    {
        parent::delete($options);

        $this->flushCache();
    }

    /**
     * Detaches a role from the user.
     *
     * @param integer|array|string|\App\Modules\RBAC\Models\Role $role
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_string($role)) {
            $role = RBAC::findRole($role);

            if (!$role) {
                return false;
            }
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    /**
     * Flushes the cache.
     *
     * @return void
     */
    public function flushCache()
    {
        $cacheKey = 'rbac_roles_for_user_'.$this->getKey();

        if ($this->cacheIsTaggable()) {
            Cache::tags('role_user')->flush();
        } else {
            Cache::forget($cacheKey);
        }
    }

    /**
     * Checks if the user has one or more specified roles.
     * If $requireAll is set to true, user mast have all the roles.
     *
     * @param string|array  $name
     * @param bool          $requireAll
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } else if (!$hasRole && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            foreach ($this->checkRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Restores the soft-deleted model.
     *
     * @return void
     */
    public function restore()
    {
        parent::restore();

        $this->flushCache();
    }

    /**
     * One-to-Many reverse relationship.
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(
            \App\Modules\RBAC\Models\Role::class,
            'role_user',
            'user_id',
            'role_id'
        );
    }

    /**
     * Saves the model.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function save(array $options = [])
    {
        $this->flushCache();

        return parent::save($options);
    }
}
