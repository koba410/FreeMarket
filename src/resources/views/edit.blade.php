@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 680px">
        <h2 class="text-center mt-5">プロフィール設定</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-center mt-5 mb-5">
                <div>
                    <img id="profileImagePreview"
                        src="{{ Storage::url($profile->profile_image ?? 'profile_image/default.jpg') }}"
                        class="rounded-circle" width="100" height="100" alt="プロフィール画像">
                    <!-- 画像選択ボタン -->
                    <input type="file" id="profileImage" name="profile_image"
                        class="d-none @error('profile_image') is-invalid @enderror" accept="image/*"
                        onchange="previewImage(event)">
                    <button type="button" class="btn btn-outline-danger"
                        onclick="document.getElementById('profileImage').click()" style="margin-left: 5rem">画像を選択する</button>

                    <!-- エラーメッセージ -->
                    @error('profile_image')
                        <span class="invalid-feedback d-block mt-3" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>


            <!-- ユーザー名 -->
            <div class="form-group mt-3">
                <label for="name">ユーザー名</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name) }}">
            </div>

            <!-- 郵便番号 -->
            <div class="form-group mt-3">
                <label for="postal_code">郵便番号</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            </div>

            <!-- 住所 -->
            <div class="form-group mt-3">
                <label for="address">住所</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="{{ old('address', $profile->address ?? '') }}">
            </div>

            <!-- 建物名 -->
            <div class="form-group mt-3">
                <label for="building">建物名</label>
                <input type="text" class="form-control" id="building" name="building"
                    value="{{ old('building', $profile->building ?? '') }}">
            </div>

            <!-- 更新ボタン -->
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-danger w-100  mb-5">更新する</button>
            </div>
        </form>
    </div>

    <script>
        // ファイルが選択されたときにプレビューを更新する関数
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('profileImagePreview');
                preview.src = reader.result; // 選択された画像をプレビューに表示
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
