<?php

namespace App\Modules\User\Models;

use App\Modules\Book\Models\Library;
use App\Modules\RBAC\Traits\UserTrait;
use App\Notifications\PasswordResetNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, UserTrait;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'photo'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    protected static function boot()
    {
        parent::boot();
        /**
         * Удаление картинок и ресайзов
         */
        static::deleted (function ($model) {
            $realPath = storage_path().'/app/public/upload/images/'. $model->photo;
            if (file_exists($realPath)) {
                if (preg_match('/(.*?)(\w+)\.(\w+)$/', $model->photo, $matches)) {
                    $files = glob(storage_path().'/app/public/upload/images/' . $matches[1] . $matches[2] . '_resize_*');
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            unlink($file);
                        }
                    }
                }
                unlink($realPath);

                if (preg_match('/^(\w+)\//', $model->photo, $matches)) {
                    $dir = storage_path().'/app/public/upload/images/' . $matches[1];
                    if (!empty($dir)) {
                        rmdir($dir);
                    }
                }
            }
        });
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

}
