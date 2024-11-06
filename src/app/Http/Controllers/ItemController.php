<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;

class ItemController extends Controller
{
    // 商品一覧ページを表示
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
                $items = auth()->user()->likedItems()->where(function ($query) use ($search) {
                    if ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    }
                })->get();
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

    // 商品詳細ページを表示
    public function show($item_id)
    {
        // 指定されたIDの商品を取得し、いいねとコメントのカウントも取得
        $item = Item::with(['categories', 'status', 'comments.user'])
            ->withCount(['likedByUsers', 'comments'])
            ->findOrFail($item_id);

        return view('detail', compact('item'));
    }

    // 商品出品ページを表示
    public function create(){
        $categories=Category::all();
        $statuses=Status::all();

        return view('listing', compact('categories', 'statuses'));
    }

    // 商品を追加
    public function store(){

    }
}
