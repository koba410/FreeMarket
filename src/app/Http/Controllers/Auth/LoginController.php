<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        // バリデーションは LoginRequest によって自動的に実行される

        // 認証処理
        if (Auth::attempt($request->only('email', 'password'))) {
            // 認証成功

            /** @var User $user */
            // ユーザーが初回ログインかどうかを確認
            $user = Auth::user();

            if ($user->first_login) {
                // 初回ログインなので、profile編集画面にリダイレクト
                $user->first_login = false;  // フラグを更新
                $user->save();  // 更新を保存
                return redirect()->route('mypage.profile');  // profile編集画面にリダイレクト
            }

            return redirect()->intended('/');
        }

        // 認証失敗時の処理
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ])->withInput($request->only('email'));
    }
}
