@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 680px">
        <h2 class="text-center mt-5">商品の出品</h2>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf

            <!-- 商品画像 -->
            <div class="form-group">
                <label for="item_image">商品画像</label>
                <div class="d-flex justify-content-center">
                    <div
                        style="width: 200px; height: 200px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center;">
                        <input type="file" id="item_image" name="item_image" class="d-none" accept="image/*"
                            onchange="previewItemImage(event)">
                        <button type="button" class="btn btn-outline-danger"
                            onclick="document.getElementById('item_image').click()">画像を選択する</button>
                    </div>
                    <img id="itemImagePreview" src="" style="max-width: 100%; max-height: 200px; display: none;" />
                </div>
            </div>

            <!-- 商品の詳細 -->
            <h5 class="mt-5">商品の詳細</h5>

            <!-- カテゴリー -->
            <div class="form-group mt-3">
                <label for="categories">カテゴリー</label>
                <div class="d-flex flex-wrap">
                    @foreach ($categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="category{{ $category->id }}"
                                name="categories[]" value="{{ $category->id }}">
                            <label class="form-check-label btn btn-outline-danger btn-sm"
                                for="category{{ $category->id }}">{{ $category->category }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 商品の状態 -->
            <div class="form-group mt-3">
                <label for="status">商品の状態</label>
                <select class="form-control" id="status" name="status">
                    <option value="">選択してください</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 商品名と説明 -->
            <h5 class="mt-5">商品名と説明</h5>

            <div class="form-group mt-3">
                <label for="title">商品名</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="商品名を入力">
            </div>

            <div class="form-group mt-3">
                <label for="description">商品の説明</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="商品の説明を入力"></textarea>
            </div>

            <!-- 販売価格 -->
            <div class="form-group mt-3">
                <label for="price">販売価格</label>
                <div class="input-group">
                    <span class="input-group-text">¥</span>
                    <input type="number" class="form-control" id="price" name="price" placeholder="価格を入力">
                </div>
            </div>

            <!-- 出品ボタン -->
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-danger w-100">出品する</button>
            </div>
        </form>
    </div>

    <script>
        function previewItemImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('itemImagePreview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
