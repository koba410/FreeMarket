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
    <header class="auth-header w-auto" style="background-color: black;">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- ロゴ (レスポンシブ対応で中央揃え) -->
                <a class="navbar-brand mx-lg-0 mx-auto" href="{{ route('item.list') }}">
                    <img class="CoachTech_White" src="{{ asset('svg/logo.svg') }}" alt="SVG Image">
                </a>

                @auth
                    <!-- トグルボタン (小さい画面用) -->
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- モバイル用ナビゲーションリンク -->
                        <ul class="navbar-nav ms-auto d-lg-none flex-row mt-3">
                            <!-- 各アイテムに余白を追加 -->
                            <li class="nav-item me-3">
                                <a class="nav-link" href="#" onclick="toggleDrawer()" style="font-size: 1.5em;">
                                    <i class="bi bi-search"></i>
                                </a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('logout') }}">ログアウト</a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('mypage', ['tab' => 'sell']) }}">マイページ</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-light" href="{{ route('sell') }}">出品</a>
                            </li>
                        </ul>

                        <!-- デスクトップ用ナビゲーションリンク -->
                        <form class="form-inline mx-auto d-none d-lg-flex" action="{{ route('item.list') }}" method="GET"
                            style="width: 300px;">
                            <input class="form-control w-100" type="search" placeholder="なにをお探しですか？" name="search"
                                value="{{ request('search') }}">
                        </form>

                        <ul class="navbar-nav ms-auto d-none d-lg-flex flex-row align-items-center">
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('logout') }}">ログアウト</a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('mypage', ['tab' => 'sell']) }}">マイページ</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-light" href="{{ route('sell') }}">出品</a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- ドロワー形式の検索フォーム (タブレット・スマホ用) -->
        <div id="searchDrawer" class="offcanvas offcanvas-top" tabindex="-1" aria-labelledby="searchDrawerLabel">
            <div class="offcanvas-header">
                <h5 id="searchDrawerLabel">検索</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('item.list') }}" method="GET">
                    <input class="form-control" type="search" placeholder="なにをお探しですか？" name="search"
                        value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </header>

    <!-- Bootstrap JavaScript (Popper.jsも含む) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- toggleDrawer 関数の定義 -->
    <script>
        function toggleDrawer() {
            const searchDrawer = new bootstrap.Offcanvas(document.getElementById('searchDrawer'));
            searchDrawer.toggle();
        }
    </script>

    <main>
        @yield('content')
    </main>
</body>

</html>
