<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{

    // ログイン画面の表示
    public function showLoginForm()
    {
        return view('auth.login');
    }


    // ログイン機能
    public function login(LoginRequest $request)
    {
        // バリデーションは LoginRequest によって自動的に実行される

        // 認証処理
        if (Auth::attempt($request->only('email', 'password'))) {
            // 認証成功

            /** @var User $user */
            // ユーザーが初回ログインかどうかを確認
            $user = Auth::user();

            if (!Profile::where('user_id', $user->id)->exists()) {
                // プロフィール編集画面にリダイレクト
                return redirect()->route('profile.edit')->with('status', 'プロフィールを登録してください。');
            }

            return redirect()->intended('/');
        }

        // 認証失敗時の処理
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ])->withInput($request->only('email'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
        // ログアウト後に/loginにリダイレクト
    }
}
