<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class AddressController extends Controller
{
    // 送付先住所変更画面の表示
    public function edit($item_id)
    {
        // ログインしているユーザーの取得
        $user = Auth::user();
        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        return view('delivary', compact('profile', 'item_id'));
    }

    // 送付先住所変更機能
    public function update(PurchaseRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Profileが存在するか確認
        if (!$profile) {
            // Profileがない場合は作成するなどの処理を追加するか、エラーハンドリングを行う
            return redirect()->back()->withErrors(['profile' => 'プロフィール情報が見つかりませんでした。']);
        }

        $profile->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase.form', ['item_id' => $request->item_id])->with('status', '住所情報が更新されました。');
    }
}
