<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
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
    public function create()
    {
        $categories = Category::all();
        $statuses = Status::all();

        return view('listing', compact('categories', 'statuses'));
    }

    // 商品を追加
    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $item = new Item();
        $item->seller_id = $user_id;
        // 画像の選択がなかった場合デフォルト画像とする。（バリデーションで選択必須にしているためおそらくその可能性は低い）
        $item->item_image = 'item_image/default.jpg';
        $item->title = $request->input('title');
        $item->brand = $request->input('brand');
        $item->description = $request->input('description');
        $item->item_status_id = $request->input('status');
        $item->price = $request->input('price');
        // 他のフィールドも同様に設定
        $item->save(); // ここで新しいIDが割り当てられる

        // カテゴリとの関連付けを保存
        $item->categories()->sync($request->categories);

        // 保存した商品のIDを取得
        $item_Id = $item->id;

        // 画像の保存
        if ($request->hasFile('item_image')) {
            $itemImage = $request->file('item_image');

            // ファイル名に商品のIDを使用して画像を保存
            $imagePath = $itemImage->storeAs(
                'item_image', // 保存するディレクトリ
                "item_{$item_Id}." . $itemImage->getClientOriginalExtension(), // ファイル名
                'public' // ディスク（publicディレクトリ）
            );

            // 画像のパスをアイテムデータに保存
            $item->item_image = $imagePath;
            $item->save(); // 商品データを再度保存して、画像パスを更新
        }

        return redirect()->route('item.show', $item_Id)->with('status', '商品が追加されました！');
    }
}
