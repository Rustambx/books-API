<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RBAC\Models\Permission;
use App\Modules\RBAC\Models\Role;
use App\Modules\Resize\CImage;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use User;
use App\Modules\User\Requests\UserRequest;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use MediaTrait, SendsPasswordResetEmails;

    public function editUser($id)
    {
        if (!auth()->user()->can('EDIT_USER')) {
            return view('403');
        }
        $this->title('User edit');

        $user = User::find($id);
        $user->resized_photo = CImage::resize($user->photo, 100, 100);
        $role_id = $user->roles->pluck('id')->toArray();
        $user->role_id = $role_id[0];

        $roles = Role::all();

        $this->view('user::edit');

        return $this->render(compact('user', 'roles'));
    }

    public function showList()
    {
        if (!auth()->user()->can('VIEW_USER_LIST')) {
            return view('403');
        }

        $users = User::all();
        $this->title('Users');

        $this->view('user::list');

        return $this->render(compact('users'));
    }

    public function addForm()
    {
        if (!auth()->user()->can('ADD_USER')) {
            return view('403');
        }

        $roles = Role::all();

        $this->title(__('Add comment'));

        $this->view('user::add');

        return $this->render(compact('roles'));
    }

    public function save(UserRequest $request)
    {
        $result = User::save($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('user')->with($result);
    }

    public function deleteUser($id)
    {
        if (!auth()->user()->can('DELETE_USER')) {
            return view('403');
        }

        $result = User::delete($id);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('user')->with($result);
    }

    /*                   API            */

    public function signIn()
    {
        return User::signIn();
    }

    public function getUserLibrary($id)
    {
        return User::getUserLibrary($id);
    }

    public function signUp(Request $request)
    {
        return User::signUp($request);
    }

    public function editProfile(Request $request, $id)
    {
        return User::editProfile($request, $id);
    }

    public function getProfile()
    {
        return User::getProfile();
    }

    /*                  Permissions                  */

    public function showPermissions()
    {
        if (!auth()->user()->can('CHANGE_PERMISSION')) {
            return view('403');
        }
        $permissions = Permission::all();
        $roles = Role::all();

        $this->title('Permissions');

        $this->view('user::permission');

        return $this->render(compact('roles', 'permissions'));
    }

    public function savePermissions(Request $request)
    {
        return User::savePermissions($request);
    }
}
