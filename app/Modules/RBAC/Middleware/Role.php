<?php

namespace App\Modules\RBAC\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Role
{
    const DELIMITER = '|';

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * Role constructor.
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
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if ($this->auth->guest() || !$request->user()->hasRole($roles)) {
            abort(403);
        }

        return $next($request);
    }
}