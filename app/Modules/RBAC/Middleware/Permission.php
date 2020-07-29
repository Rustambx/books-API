<?php

namespace App\Modules\RBAC\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Permission
{
    const DELIMITER = '|';

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * Permission constructor.
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
     * @param         $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if ($this->auth->guest() || !$this->auth->user()->can($permissions)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}