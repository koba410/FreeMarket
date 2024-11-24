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

    // プロフィール画面の表示
    public function show(Request $request)
    {
        // ログインしているユーザーの取得
        $user = Auth::user();

        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        // アイテム情報のデフォルト設定
        $items = collect();

        // 出品した商品か購入した商品かで表示を切り替える
        if ($request->input('tab') === 'sell') {
            // 出品した商品を取得（売れているかは別でチェックしたい場合）
            $items = Item::where('seller_id', $user->id)->get();
        } elseif ($request->input('tab') === 'buy') {
            // 購入した商品を取得
            $items = Purchase::where('buyer_id', $user->id)->with('item')->get()->pluck('item');
        }

        // プロフィール画面を表示
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
