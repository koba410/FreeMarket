@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px">

        <!-- タブ切り替え -->
        <div class="text-center mt-4">
            <a href="{{ route('item.list') }}"
                class="{{ request('tab') !== 'mylist' ? 'font-weight-bold text-danger' : '' }}">おすすめ</a>
            |
            <a href="{{ route('item.list', ['tab' => 'mylist']) }}"
                class="{{ request('tab') === 'mylist' ? 'font-weight-bold text-danger' : '' }}">マイリスト</a>
        </div>

        <!-- 商品一覧 -->
        <div class="row mt-4">
            @forelse($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <!-- 商品画像 -->
                        <div class="card-img-top" style="background-color: #e0e0e0; height: 200px; text-align: center;">
                            @if ($item->is_sold)
                                <span class="badge badge-danger"
                                    style="position: absolute; top: 10px; left: 10px;">Sold</span>
                            @endif
                            <img src="{{ asset($item->image_path ?? 'img/default.png') }}" alt="商品画像"
                                style="height: 100%; max-width: 100%; object-fit: cover;">
                        </div>
                        <!-- 商品名 -->
                        <div class="card-body text-center">
                            <p>{{ $item->name }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center mt-5">表示する商品がありません</p>
            @endforelse
        </div>
    </div>
@endsection
