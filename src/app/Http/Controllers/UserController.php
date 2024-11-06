<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function show(Request $request)
    {
        // ログインしているユーザーの取得
        $user = Auth::user();

        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        // アイテム情報を取得
        $query = Item::query();

        // 購入情報を取得
        $buy_items = Purchase::query();

        // デフォルトの $items 値を設定
        $items = collect();


        // 出品した商品か購入した商品か
        if ($request->input('tab') === 'sold') {
            // 出品した商品なら
            $items = $query->where('seller_id', '=', auth()->id())->get();
        }
        if ($request->input('tab') === 'bought') {
            // 購入した商品なら
            $items = $buy_items->where('buyer_id', '=', auth()->id())->get();
        }

        // プロフィール編集画面を表示
        return view('profile', compact('user', 'profile', 'items'));
    }

    // プロフィール編集画面の表示
    public function edit()
    {
        // ログインしているユーザーの取得
        $user = Auth::user();

        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        // プロフィール編集画面を表示
        return view('edit', compact('user', 'profile'));
    }

    // プロフィール追加または更新
    public function update(ProfileRequest $request)
    {

        // ログインしているユーザーのプロフィールをアップサート
        $user = Auth::user();
        $user_id = $user->id;

        /** @var User $user */
        // users テーブルの name カラムを更新
        $user->update([
            'name' => $request->name,
        ]);

        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            // 新しい画像がアップロードされた場合
            $profileImage = $request->file('profile_image');
            $imagePath = $profileImage->storeAs('profile_image', "profile_{$user_id}." . $profileImage->getClientOriginalExtension(), 'public');
        } else {
            // プロフィールが既に存在している場合は、その画像パスを使用
            $existingProfile = Profile::where('user_id', $user_id)->first();
            $imagePath = $existingProfile ? $existingProfile->profile_image : 'profile_image/default.jpg';
        }

        // user_profiles テーブルのプロフィール情報をアップサート
        Profile::updateOrCreate(
            ['user_id' => $user_id],  // 検索条件に user_id を指定
            [
                'user_id' => $user->id,  // 新規作成時に user_id を明示的に指定
                'postal_code' => $request->postal_code ?? '',
                'address' => $request->address ?? '',
                'building' => $request->building ?? '',
                'profile_image' => $imagePath,
            ]
        );

        return redirect()->route('item.list')->with('status', 'プロフィールが更新されました');
    }
}
