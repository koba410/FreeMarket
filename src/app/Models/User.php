<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // インターフェイスをインポート
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailJP;

class User extends Authenticatable implements MustVerifyEmail // インターフェイスを実装
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // profileとのリレーション
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id','id');
    }

    // itemとのリレーション
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    // 「いいね」機能とのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    // コメントとのリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    // 購入情報とのリレーション
    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'user_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailJP);
    }
}
