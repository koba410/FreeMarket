@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px">
        <!-- ユーザー情報 -->
        <div class="d-flex justify-content-evenly align-items-center mt-5">
            <!-- プロフィール画像 -->
            <div class="d-flex justify-content-around align-items-center" style="min-width: 200px">
                <img src="{{ Storage::url($user->profile->profile_image ?? 'profile_image/default.jpg') }}"
                    class="rounded-circle" width="80" height="80" alt="プロフィール画像">
                <!-- ユーザー名 -->
                <h3 class="m-0">{{ $user->name }}</h3>
            </div>
            <!-- プロフィールを編集ボタン -->
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-danger">プロフィールを編集</a>
        </div>

        <!-- タブ切り替え -->
        <div class="text-left mt-5">
            <a href="{{ route('mypage', ['tab' => 'sell']) }}"
                class="{{ request('tab') !== 'buy' ? 'font-weight-bold text-danger' : '' }}" >出品した商品</a>
            |
            <a href="{{ route('mypage', ['tab' => 'buy']) }}"
                class="{{ request('tab') === 'buy' ? 'font-weight-bold text-danger' : '' }}">購入した商品</a>
        </div>

        <!-- 商品一覧 -->
        <div class="row mt-4 pt-3 full-width-line">
            @forelse($items as $item)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('item.show', $item->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="card">
                            <!-- 商品画像 -->
                            <div class="card-img-top" style="background-color: #e0e0e0; height: 200px; text-align: center;">
                                @if ($item->is_sold)
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                                        Sold
                                    </div>
                                @endif

                                <img src="{{ Storage::url($item->item_image ?? 'item_image/default.png') }}" alt="商品画像"
                                    style="height: 100%; max-width: 100%; object-fit: cover;">
                            </div>
                            <!-- 商品名 -->
                            <div class="card-body text-center">
                                <p>{{ $item->title }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-center mt-5">表示する商品がありません</p>
            @endforelse
        </div>
    </div>
@endsection
