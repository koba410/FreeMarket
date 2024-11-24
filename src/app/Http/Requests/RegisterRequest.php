<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを承認されているかどうかを判断
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルールを定義
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required','email'],
            'password' => ['required','min:8', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'same:password'],
        ];
    }

    /**
     * カスタムエラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => '',
            'password_confirmation.required' => '確認用パスワードを入力してください。',
            'password_confirmation.min' => '確認用パスワードも8文字以上入力してください。',
            'password_confirmation.same' => 'パスワードが一致しません。',
        ];
    }
}
