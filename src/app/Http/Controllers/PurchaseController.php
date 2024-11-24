<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    // 購入画面を表示する
    public function showPurchaseForm($item_id)
    {
        // 商品情報を取得
        $item = Item::findOrFail($item_id);

        // ログインしているユーザーの取得
        $user = Auth::user();
        // プロフィール情報の取得
        $profile = $user->profile->fresh(); // リレーションで取得

        // 購入画面のビューを表示
        return view('purchase', compact('item', 'profile'));
    }

    // クレジットカード決済
    public function cardCheckout($item_id)
    {
        $item = Item::findOrFail($item_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id, 'method' => 'card']),
            'cancel_url' => route('purchase.cancel', ['item_id' => $item_id]),
        ]);

        return redirect($session->url, 303);
    }

    // コンビニ決済
    public function convenienceCheckout($item_id)
    {
        $item = Item::findOrFail($item_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id, 'method' => 'konbini']),
            'cancel_url' => route('purchase.cancel', ['item_id' => $item_id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => Auth::id(),
                'postal_code' => Auth::user()->profile->postal_code,
                'address' => Auth::user()->profile->address,
                'building' => Auth::user()->profile->building,
            ],
        ]);

        return redirect($session->url, 303);
    }

    //クレカ支払い成功時
    public function success($item_id, $method)
    {
        // 商品情報を取得
        $item = Item::findOrFail($item_id);

        // ログインしているユーザーの取得
        $user = Auth::user();
        // プロフィール情報の取得
        $profile = $user->profile; // リレーションで取得

        // purchasesテーブルに新しいレコードを追加
        Purchase::create([
            'item_id' => $item_id, // 決済成功した商品ID
            'buyer_id' => $user->id, // 購入者ID（現在のユーザー）
            'delivary_postal_code' => $profile->postal_code,
            'delivary_address' => $profile->address,
            'delivary_building' => $profile->building,
            'payment_method' => $method, // 例えば、'card'や'convenience'
        ]);

        // 商品が購入済みになったため、itemsテーブルのis_soldをtrueに更新
        $item->update(['is_sold' => true]);

        // 購入成功画面のビューを表示
        return view('success', compact('item'));
    }

    // クレカ失敗時
    public function cancel($item_id)
    {
        // 商品情報を取得
        $item = Item::findOrFail($item_id);
        // 購入失敗画面のビューを表示
        return view('cancel', compact('item'));
    }

    // コンビニ支払い成功時
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['status' => 'signature verification failed'], 400);
        }

        if ($event->type === 'checkout.session.async_payment_succeeded') {
            $session = $event->data->object;

            $item_id = $session->metadata->item_id ?? null;
            $user_id = $session->metadata->user_id ?? null;
            $postal_code = $session->metadata->postal_code ?? null;
            $address = $session->metadata->address ?? null;
            $building = $session->metadata->building ?? null;
            $method = $session->payment_method_types[0] ?? null;

            if (!$item_id || !$method || !$user_id) {
                return response()->json(['status' => 'metadata missing'], 400);
            }

            try {
                Purchase::create([
                    'item_id' => $item_id,
                    'buyer_id' => $user_id,
                    'delivary_postal_code' => $postal_code,
                    'delivary_address' => $address,
                    'delivary_building' => $building,
                    'payment_method' => $method,
                ]);

                Item::where('id', $item_id)->update(['is_sold' => true]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'database save error'], 500);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
