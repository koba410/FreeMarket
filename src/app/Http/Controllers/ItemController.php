<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // 商品検索機能
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // マイリストタブかどうかの確認
        if ($request->input('tab') === 'mylist') {
            // マイリスト表示：認証ユーザーでいいねした商品を取得
            if (auth()->check()) {
                $items = auth()->user()->likes()->get();
            } else {
                $items = collect(); // 未認証の場合は空のコレクションを返す
            }
        } else {
            // 全商品表示：自分が出品した商品を除外
            $items = $query->where('seller_id', '!=', auth()->id())
                // ->where('is_sold', false) // 未購入の商品を表示
                ->get();
        }

        return view('index', compact('items'));
    }
}
