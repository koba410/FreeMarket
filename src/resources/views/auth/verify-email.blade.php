@extends('layouts.app')

@section('content')
<div class="container">
    <h2>メール認証の確認</h2>
    <p>ご登録のメールアドレスに認証リンクを送信しました。メールを確認し、リンクをクリックして認証を完了してください。</p>
    <p>もしメールが届かない場合は、再度送信してください。</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">認証メールを再送する</button>
    </form>
</div>
@endsection
