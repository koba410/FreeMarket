<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'brand',
        'price',
        'item_image',
        'description',
        'is_sold',
    ];

    // 出品者（ユーザー）とのリレーション
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Categoryモデルとの多対多リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'item_likes')->withTimestamps();
    }

    // コメントとのリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    // 購入情報とのリレーション
    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'item_id');
    }

    // 商品ステータスとのリレーション
    public function status()
    {
        return $this->belongsTo(Status::class, 'item_status_id');
    }
}
