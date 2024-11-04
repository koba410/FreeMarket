<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    // メールアドレス認証機能
    public function verify(EmailVerificationRequest $request)
    {
        // メールアドレスの認証を完了する
        $request->fulfill();
        // ユーザーを自動でログインさせる
        $user = User::find($request->user()->id);
        Auth::login($user);
        
        return redirect()->route('profile.edit'); // 認証後のリダイレクト先
    }

    // メールアドレス認証確認画面の表示
    public function show()
    {
        return view('auth.verify-email');
    }

    // メールアドレス認証メールの再送信機能
    public function send()
    {
        auth()->user()->sendEmailVerificationNotification();

        return back()->with('status', '認証メールを再送しました！');
    }
}
