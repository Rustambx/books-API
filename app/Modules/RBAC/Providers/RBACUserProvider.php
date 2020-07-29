<?php

namespace App\Modules\RBAC\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class RBACUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     * OVERRIDE
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        $query->where(function ($q) use ($credentials) {
            if (isset($credentials['email'])) {
                $q->where('email', $credentials['email']);
            } else {
                if (isset($credentials['phone'])) {
                    $q->where('phone', json_encode($credentials['phone']));
                }
            }
        });

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     * OVERRIDE
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}