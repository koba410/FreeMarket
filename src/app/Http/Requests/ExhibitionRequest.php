<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'brand' => ['nullable'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'max:255'],
            'item_image' => ['required', 'mimes:png,jpeg'],
            'categories' => ['required', 'array', 'min:1'],
            'status' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'item_image.required' => '写真をアップロードしてください。',
            'item_image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください。',
            'categories.required' => 'カテゴリを選んでください。',
            'status.required' => '商品の状態を選択してください。',
            'price.required' => '販売価格を設定してください。',
            'price.integer' => '販売価格を設定してください。',
            'price.min' => '販売価格にマイナスは設定できません。',
        ];
    }
}
