<?php

namespace App\Modules\RBAC\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Ability
{
    const DELIMITER = '|';

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * Ability constructor.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the incoming request.
     *
     * @param         $request
     * @param Closure $next
     * @param         $roles
     * @param         $permissions
     * @param bool    $validateAll
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {
        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if (!is_bool($validateAll)) {
            $validateAll = filter_var($validateAll, FILTER_VALIDATE_BOOLEAN);
        }

        if ($this->auth->guest() || !$request->user()->ability($roles, $permissions, [
                'validate_all' => $validateAll
            ])) {
            abort(403);
        }

        return $next($request);
    }
}