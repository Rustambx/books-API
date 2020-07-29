<?php

namespace App\Modules\RBAC\Contracts;

interface RoleInterface
{
    public function attachPermission($permission);

    public function attachPermissions($permissions);

    public function detachPermission($permission);

    public function detachPermissions($permissions);

    public function perms();

    public function savePermissions($inputPermissions);
}