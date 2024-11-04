<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリマアプリ</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- カスタム CSS --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <header class="auth-header w-auto" style="background-color: black">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <!-- ロゴ -->
                <a class="navbar-brand" href="{{ route('item.list') }}">
                    <img class="CoachTech_White" src="{{ asset('svg/logo.svg') }}" alt="SVG Image">
                </a>

                @auth
                    <!-- 検索フォーム -->
                    <form class="form-inline mx-auto" action="{{ route('item.list') }}" method="GET"
                        style="width: 300px;">
                        <input class="form-control w-100" type="search" placeholder="なにをお探しですか？" name="search"
                            value="{{ request('search') }}">
                    </form>

                    <!-- ナビゲーションリンク -->
                    <div class="ml-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}">ログアウト</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mypage') }}">マイページ</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-light" href="{{ route('sell') }}">出品</a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
