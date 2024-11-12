@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 680px;">
        <h2 class="text-center mt-5">住所の変更</h2>

        <form action="{{ route('address.update') }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')

            <!-- 郵便番号 -->
            <div class="form-group mt-3">
                <label for="postal_code">郵便番号</label>
                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                    name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                @error('postal_code')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- 住所 -->
            <div class="form-group mt-3">
                <label for="address">住所</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                    name="address" value="{{ old('address', $profile->address ?? '') }}">
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- 建物名 -->
            <div class="form-group mt-3">
                <label for="building">建物名</label>
                <input type="text" class="form-control @error('building') is-invalid @enderror" id="building"
                    name="building" value="{{ old('building', $profile->building ?? '') }}">
                @error('building')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- 更新ボタン -->
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-danger w-100">更新する</button>
            </div>
            <input type="hidden" name="item_id" value="{{ $item_id }}">
        </form>
    </div>
@endsection
