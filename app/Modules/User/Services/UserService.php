<?php

namespace App\Modules\User\Services;

use App\Modules\RBAC\Models\Role;
use App\Modules\RBAC\Traits\UserTrait;
use App\Modules\Resize\CImage;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\UserRequest;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;

class UserService
{
    use MediaTrait, UserTrait;

    public function all()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->resized_photo = CImage::resize($user->photo, 100, 100);
        }

        return $users;
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user->delete()) {
            $user->roles()->detach();
            return ['status' => 'Пользователь удален'];
        } else {
            return ['error' => 'Ошибка при удалении'];
        }
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function save(UserRequest $request)
    {
        if ($request->has('edit')) {
            return $this->update($request);
        } else {
            return $this->create($request);
        }
    }

    public function update(UserRequest $request)
    {
        $data = $request->except('_token', '_method', 'id', 'edit', 'photo');
        if (!empty($data['password'])) {
            if ($data['password'] == $data['password_confirmation']) {
                $data['password'] = bcrypt($data['password']);
            } else {
                return ["error" => "Вы неправильно повторили пароль"];
            }
        } else {
            unset($data['password']);
        }
        $user = User::find($request->id);

        if (!empty($request->hasFile('photo'))) {
            $photo = $request->file('photo');
            if ($this->checkImageMimeType($photo)) {
                $data['photo'] = $this->uploadImage($photo);
            } else {
                return ['error' => 'Доступны только jpg и png форматы изображений'];
            }

            $realPath = storage_path().'/app/public/upload/images/'. $user->photo;
            $oldImageFile = $user->photo;
            if (!empty($oldImageFile) && file_exists(storage_path().'/app/public/upload/images/'.$oldImageFile)) {
                if (preg_match('/(.*?)(\w+)\.(\w+)$/', $oldImageFile, $matches)) {
                    $files = glob(storage_path().'/app/public/upload/images/' . $matches[1] . $matches[2] . '_resize_*');
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            unlink($file);
                        }
                    }
                }
                unlink($realPath);

                if (preg_match('/^(\w+)\//', $user->photo, $matches)) {
                    $dir = storage_path().'/app/public/upload/images/' . $matches[1];
                    if (!empty($dir)) {
                        rmdir($dir);
                    }
                }
            }
        }

        if ($user->update($data)) {
            $user->roles()->sync($request->input('role_id'));
            return ['status' => 'Пользователь обновлен'];
        } else {
            return ['error' => 'Ошибка при сохранении'];
        }
    }

    public function create(UserRequest $request)
    {
        $data = $request->except('_token', '_method', 'id', 'edit', 'photo', 'role_id');
        if ($request->file('photo')) {
            $photo = $request->file('photo');
            if ($this->checkImageMimeType($photo)) {
                $data['photo'] = $this->uploadImage($photo);
            } else {
                return ['error' => 'Доступны только jpg и png форматы изображений'];
            }
        }

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        if ($user) {
            $user->attachRole($request->input('role_id'));
            return ['status' => 'Пользователь добавлен'];
        } else {
            return ['error' => 'Ошибка при сохранении'];
        }
    }

    /*                   API                */

    public function signIn()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUserLibrary($id)
    {
        $user = User::find($id);
        $books_id = $user->libraries()->pluck('book_id');
        $user->libraries = Book::all()->whereIn('id', $books_id);

        return response()->json([
            'status' => 1,
            'books' => $user->libraries
        ]);
    }

    public function signUp(Request $request)
    {
        $image = $request->file('photo');
        $save_image = $this->uploadImage($image);
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'photo' => $save_image,
        ]);

        $user->attachRole(3);

        return response()->json([
            'status' => 1,
            'success' => 'Пользователь успешно зарегистрирован'
        ]);
    }

    public function editProfile(Request $request, $id)
    {
        $user = User::find($id);
        $photo = $request->file('photo');
        $save_image = $this->uploadImage($photo);

        $realPath = storage_path().'/app/public/upload/images/'. $user->photo;
        $oldImageFile = $user->photo;
        if (!empty($oldImageFile) && file_exists(storage_path().'/app/public/upload/images/'.$oldImageFile)) {
            if (preg_match('/(.*?)(\w+)\.(\w+)$/', $oldImageFile, $matches)) {
                $files = glob(storage_path().'/app/public/upload/images/' . $matches[1] . $matches[2] . '_resize_*');
                if (is_array($files)) {
                    foreach ($files as $file) {
                        unlink($file);
                    }
                }
            }
            unlink($realPath);

            if (preg_match('/^(\w+)\//', $user->photo, $matches)) {
                $dir = storage_path().'/app/public/upload/images/' . $matches[1];
                if (!empty($dir)) {
                    rmdir($dir);
                }
            }
        }

        $user->update([
            'description' => $request->input('description'),
            'name' => $request->input('user_id'),
            'book_id' => $request->input('book_id'),
            'photo' => $save_image
        ]);

        return response()->json([
            'status' => 1,
            'success' => 'Пользователь успешно обновлен'
        ]);

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function getProfile()
    {
        return response()->json(auth()->user());
    }

    /*                  Permissions                  */

    public function savePermissions(Request $request)
    {
        $data = $request->except('_token');
        $roles = Role::all();

        foreach ($roles as $role) {
            if (isset($data[$role->id])) {
                $role->savePermissions($data[$role->id]);
            } else {
                $role->savePermissions([]);
            }
        }
        return redirect()->route('user.permission');
    }

}
