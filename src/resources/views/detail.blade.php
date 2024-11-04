@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
        <div class="row mt-5">
            <!-- 商品画像 -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center"
                        style="height: 350px; background-color: #e0e0e0;">
                        <img src="{{ Storage::url($item->item_image ?? 'img/default.png') }}" alt="商品画像"
                            style="height: 100%; max-width: 100%; object-fit: cover;">
                    </div>
                    <div class="card-footer text-center">商品画像</div>
                </div>
            </div>

            <!-- 商品情報 -->
            <div class="col-md-7">
                <h3>{{ $item->title }}</h3>
                <p>{{ $item->brand }}</p>
                <h4 class="text-danger">¥{{ number_format($item->price) }} <small>（税込）</small></h4>

                <!-- いいね・コメントアイコンと購入ボタン -->
                <div class="d-flex justify-content-around align-items-center mb-3">
                    <div class="mr-3">
                        <!-- いいねボタン -->
                        @if (Auth::check() && Auth::user()->likedItems->contains($item->id))
                            <!-- いいね済みの場合は解除ボタンを表示 -->
                            <form action="{{ route('item.unlike', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">いいね解除</button>
                            </form>
                        @else
                            <!-- いいねしていない場合は追加ボタンを表示 -->
                            <form action="{{ route('item.like', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">いいね</button>
                            </form>
                        @endif
                        <!-- いいね数の表示 -->
                        <p>いいね数: {{ $item->liked_by_users_count }}</p>
                    </div>
                    <div class="mr-3">
                        <i class="far fa-comment"></i> <span>{{ $item->comments_count ?? 0 }}</span>
                    </div>
                    <a href="{{-- route('purchase', $item->id) --}}" class="btn btn-danger ml-auto">購入手続きへ</a>
                </div>

                <!-- 商品説明 -->
                <div class="mt-4">
                    <h5>商品説明</h5>
                    <p>{{ $item->description }}</p>
                </div>

                <!-- 商品の情報 -->
                <div class="mt-4">
                    <h5>商品の情報</h5>
                    <p><strong>カテゴリー：</strong>
                        @foreach ($item->categories as $category)
                            <span class="badge"
                                style="background-color: #6c757d; color: #ffffff; border: 1px solid #5a6268; padding: 0.35em 0.65em; border-radius: 0.25rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $category->category }}</span>
                        @endforeach
                    </p>
                    <p><strong>商品の状態：</strong> {{ $item->status->status ?? '良好' }}</p>
                </div>
            </div>
        </div>

        <!-- コメント一覧 -->
        <div class="mt-5">
            <h5>コメント ({{ $item->comments->count() }})</h5>
            @forelse($item->comments as $comment)
                <div class="media mt-3">
                    <img src="{{ asset('img/default-avatar.png') }}" class="mr-3 rounded-circle" width="40"
                        height="40" alt="ユーザーアイコン">
                    <div class="media-body">
                        <h6 class="mt-0">{{ $comment->user->name }}</h6>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted mt-3">こちらにコメントが入ります。</p>
            @endforelse
        </div>

        <!-- コメント入力フォーム -->
        <div class="mt-4">
            <h5>商品へのコメント</h5>
            <form action="{{-- route('comment.store', $item->id) --}}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" placeholder="コメントを入力してください"></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100">コメントを送信する</button>
            </form>
        </div>
    </div>
@endsection
