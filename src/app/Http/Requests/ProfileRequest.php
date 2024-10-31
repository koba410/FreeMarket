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
            'img.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
