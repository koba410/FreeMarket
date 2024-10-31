<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // メールアドレスの認証を完了する
        return redirect()->route('profile.edit'); // 認証後のリダイレクト先
    }

    public function show()
    {
        return view('auth.verify-email');
    }

    public function send()
    {
        auth()->user()->sendEmailVerificationNotification();

        return back()->with('status', '認証メールを再送しました！');
    }
}
