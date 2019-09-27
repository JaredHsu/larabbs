<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use App\Models\Topic;

use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable, MustVerifyEmailTrait;

    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

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

    public function topics() 
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    // use Notifiable {
    //     notify as protected laravelNotify;
    // }

    //重写nofify以适应通知
    //  public function notify($instance)
    // {
    //     // 如果要通知的人是当前用户，就不必通知了！
    //     if ($this->id == Auth::id()) {
    //         return;
    //     }

    //     // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
    //     if (method_exists($instance, 'toDatabase')) {
    //         $this->increment('notification_count');
    //     }

    //     $this->laravelNotify($instance);
    // }

    //替代上面消息通知文案，更优解
    public function topicNotify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->notify($instance);
    }

    public function setPasswordAttribute($value)
    {
        if(strlen($value) != 60)
        {
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! \Str::startsWith($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/upload/image/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

}
