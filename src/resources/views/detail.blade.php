@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
        <div class="row mt-5">
            <!-- 商品画像 -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <img src="{{ Storage::url($item->item_image ?? 'item_image/default.png') }}" alt="商品画像"
                            style="height: 100%; max-width: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>

            <!-- 商品情報 -->
            <div class="col-md-6">
                <h2>{{ $item->title }}</h2>
                <p>{{ $item->brand }}</p>
                <h3>¥{{ number_format($item->price) }} <small>（税込）</small></h3>

                <!-- いいね・コメントアイコンと購入ボタン -->
                <div class="d-flex justify-content-around align-items-center mt-5 mb-3">
                    <div class="like-section">
                        <!-- いいねボタン -->
                        @if (Auth::check() && Auth::user()->likedItems->contains($item->id))
                            <!-- いいね済みの場合は解除ボタンを表示 -->
                            <form action="{{ route('item.unlike', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0">
                                    <i class="bi bi-heart-fill text-danger" style="font-size: 1.5em;"></i>
                                    <!-- 塗りつぶしハートアイコン -->
                                </button>
                            </form>
                        @else
                            <!-- いいねしていない場合は追加ボタンを表示 -->
                            <form action="{{ route('item.like', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link p-0">
                                    <i class="bi bi-heart" style="font-size: 1.5em; color: #6c757d;"></i>
                                    <!-- 輪郭のみのハートアイコン -->
                                </button>
                            </form>
                        @endif
                        <!-- いいね数の表示 -->
                        <p class="like-count">{{ $item->liked_by_users_count }}</p>
                    </div>
                    <div class="comment-section">
                        <i class="bi bi-chat"></i> <span class="comment-count">{{ $item->comments_count ?? 0 }}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('purchase.form', $item->id) }}" class="btn btn-danger w-100">購入手続きへ</a>
                </div>

                <!-- 商品説明 -->
                <div class="mt-5 mb-3">
                    <h5>商品説明</h5>
                    <p>{{ $item->description }}</p>
                </div>

                <!-- 商品の情報 -->
                <div class="mt-5">
                    <h5 class="mb-3">商品の情報</h5>
                    <p><strong>カテゴリー：</strong>
                        @foreach ($item->categories as $category)
                            <span class="badge"
                                style="background-color: #6c757d; color: #ffffff; border: 1px solid #5a6268; padding: 0.35em 0.65em; border-radius: 0.25rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $category->category }}</span>
                        @endforeach
                    </p>
                    <p><strong>商品の状態：</strong> {{ $item->status->status ?? '良好' }}</p>
                </div>

                <!-- コメント一覧 -->
                <div class="mt-5">
                    <h5>コメント ({{ $item->comments->count() }})</h5>
                    @foreach ($item->comments as $comment)
                        <div class="media mt-3">
                            <div class="d-flex align-items-start align-items-center">
                                <img src="{{ Storage::url($comment->user->profile->profile_image ?? 'profile_image/default.jpg') }}"
                                    class="mr-3 rounded-circle" width="40" height="40" alt="ユーザーアイコン">
                                <h6 class="mt-0" style="margin-left: 1rem;">{{ $comment->user->name }}</h6>
                            </div>
                            <div class="media-body d-flex align-items-start align-items-center mt-3"
                                style="margin-left: 2rem; max-width: 100%;">
                                <div class="comment-bubble">
                                    <p>{{ $comment->comment }}</p>
                                </div>
                                @if (Auth::check() && Auth::id() === $comment->user_id)
                                    <form action="{{ route('comment.destroy', $comment) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            style="min-width: 50px; margin-left: 1rem; margin-right: 1rem;">削除</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- コメント入力フォーム -->
                <div class="mt-5 mb-5">
                    <h5>商品へのコメント</h5>
                    @auth
                        <form action="{{ route('comment.store', $item) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">コメントを投稿</button>
                        </form>
                    @endauth
                    @guest
                        <p>コメントを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
