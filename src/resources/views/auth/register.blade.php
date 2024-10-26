@extends('layouts.app')

@section('content')
    <!-- メインコンテンツ -->
    <div class="row justify-content-center">
        <div class="col-md-6" style="max-width: 680px">
            <h2 class="text-center mt-5 mb-4">会員登録</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- ユーザー名 -->
                <div class="form-group mb-4">
                    <label for="name">ユーザー名</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- メールアドレス -->
                <div class="form-group mb-4">
                    <label for="email">メールアドレス</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- パスワード -->
                <div class="form-group mb-4">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- 確認用パスワード -->
                <div class="form-group mb-5">
                    <label for="password-confirm">確認用パスワード</label>
                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                </div>

                <!-- 登録ボタン -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-danger">登録する</button>
                </div>

                <!-- ログインリンク -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-primary">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
@endsection
