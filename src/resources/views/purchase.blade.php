@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div class="row mt-5">
            <!-- 商品情報部分 -->
            <div class="col-md-7">
                <div class="d-flex">
                    <div>
                        <img src="{{ Storage::url($item->item_image ?? 'item_image/default.png') }}" alt="商品画像"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="ml-4" style="margin-left: 2rem;">
                        <h4>{{ $item->title }}</h4>
                        <p class="fs-5">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <!-- 支払い方法 -->
                <h5>支払い方法</h5>
                <div class="form-group mt-3">
                    <select class="form-control" id="paymentMethod" name="paymentMethod">
                        <option value="" disabled selected>選択してください</option>
                        <option value="convenience">コンビニ支払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                </div>

                <hr class="my-4">

                <!-- 配送先情報 -->
                <h5>配送先</h5>
                <p>{{ $profile->postal_code }} <br> {{ $profile->address }} <br>{{ $profile->building }}</p>
                <a href="{{ route('address.edit', $item->id) }}" class="text-primary">変更する</a>

                <hr class="my-4">

            </div>

            <!-- 右側の注文概要部分 -->
            <div class="col-md-5">
                <div class="card p-3">
                    <div class="d-flex justify-content-between">
                        <p>商品代金</p>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>支払い方法</p>
                        <p id="selectedPaymentMethod">選択されていません</p>
                    </div>
                </div>
                <!-- 支払い方法フォーム -->
                <form id="purchaseForm" action="" method="POST">
                    @csrf
                    <button type="button" class="btn btn-danger btn-block mt-4 w-100 p-2" id="purchaseButton">購入する</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 支払い方法を選択したときに、右側の注文概要に反映させる
        document.getElementById('paymentMethod').addEventListener('change', function() {
            const selectedMethod = this.options[this.selectedIndex].text;
            document.getElementById('selectedPaymentMethod').textContent = selectedMethod;
        });

        // Stripeの購入ボタン処理
        document.getElementById('purchaseButton').addEventListener('click', function() {
            const paymentMethod = document.getElementById('paymentMethod').value;

            // 配送先の郵便番号と住所が空欄かどうかをチェック
            const postalCode = "{{ $profile->postal_code }}";
            const address = "{{ $profile->address }}";

            if (!paymentMethod) {
                alert('支払い方法を選択してください');
                return;
            }

            if (!postalCode || !address) {
                alert('配送先を編集してください');
                return;
            }

            const purchaseForm = document.getElementById('purchaseForm');

            // 支払い方法に応じたフォームのアクションを設定
            if (paymentMethod === 'card') {
                purchaseForm.action = "{{ route('stripe.cardCheckout', ['item_id' => $item->id]) }}";
            } else if (paymentMethod === 'convenience') {
                purchaseForm.action = "{{ route('stripe.convenienceCheckout', ['item_id' => $item->id]) }}";
            }

            // フォームを送信
            purchaseForm.submit();
        });
    </script>
@endsection
