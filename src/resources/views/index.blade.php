@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px">

        <!-- タブ切り替え -->
        <div class="text-center mt-4">
            <a href="{{ route('item.list', ['search' => request('search')]) }}"
                class="{{ request('tab') !== 'mylist' ? 'font-weight-bold text-danger' : '' }}">おすすめ</a>
            |
            <a href="{{ route('item.list', ['tab' => 'mylist', 'search' => request('search')]) }}"
                class="{{ request('tab') === 'mylist' ? 'font-weight-bold text-danger' : '' }}">マイリスト</a>
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

                                <img src="{{ Storage::url($item->item_image ?? 'item_image/default.jpg') }}" alt="商品画像"
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
