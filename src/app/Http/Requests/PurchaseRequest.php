<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'item_id' => ['required'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'postal_code.required' => "郵便番号を入力してください",
            'postal_code.regex' => '郵便番号をハイフン付きで入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
