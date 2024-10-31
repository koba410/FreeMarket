<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function edit()
    {
        // ログインしているユーザーの取得
        $user = Auth::user();

        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        // プロフィール編集画面を表示
        return view('edit', compact('user', 'profile'));
    }

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
            // 画像が設定されていない場合はデフォルト画像
            $imagePath = 'profile_image/default.jpg';
        }

        // user_profiles テーブルのプロフィール情報をアップサート
        Profile::updateOrCreate(
            ['user_id' => $user_id],  // 検索条件に user_id を指定
            [
                'user_id' => $user->id,  // 新規作成時に user_id を明示的に指定
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
                'profile_image' => $imagePath,
            ]
        );

        return back()->with('status', 'プロフィールが更新されました');
    }
}
