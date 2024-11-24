@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 700px; text-align: center; margin-top: 50px;">
        <h2 class="text-danger mb-4">この商品の購入に失敗しました。</h2>

        <!-- 商品情報 -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">{{ $item->title }}</h4>
                <p>¥{{ number_format($item->price) }}</p>
                <img src="{{ Storage::url($item->item_image ?? 'img/default.png') }}" alt="商品画像" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
        </div>

        <!-- 購入画面へ戻るボタン -->
        <a href="{{ route('purchase.form', ['item_id' => $item->id]) }}" class="btn btn-warning">もう一度購入画面に戻る</a>
    </div>
@endsection
