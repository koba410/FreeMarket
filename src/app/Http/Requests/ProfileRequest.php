<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'name'=>['required'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['nullable'],
            'profile_image' => ['mimes:png,jpeg'],
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
            'name.required'=>"ユーザーネームを入力してください",
            'postal_code.required' => "郵便番号を入力してください",
            'postal_code.regex' => '郵便番号をハイフン付きで入力してください',
            'address.required' => '住所を入力してください',
            'profile_image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
