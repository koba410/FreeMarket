<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemLikeController extends Controller
{
    // いいねを追加する
    public function store(Item $item)
    {
        $user = Auth::user();

        // ログインしていない場合はログインページにリダイレクト
        if (!$user) {
            return redirect()->route('login')->with('error', 'いいね機能を使用するにはログインが必要です。');
        }

        // すでにいいねしていない場合のみ追加
        if (!$user->likedItems()->where('item_id', $item->id)->exists()) {
            $user->likedItems()->attach($item->id);
        }

        return redirect()->back();
    }

    // いいねを解除する
    public function destroy(Item $item)
    {
        $user = Auth::user();

        // いいねが存在する場合のみ削除
        if ($user->likedItems()->where('item_id', $item->id)->exists()) {
            $user->likedItems()->detach($item->id);
        }

        return redirect()->back();
    }
}
