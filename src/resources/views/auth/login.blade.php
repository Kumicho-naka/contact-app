@extends('layouts.app')

@section('title', 'Login | FashionablyLate')
@section('page-title', 'Login')

@section('header-right')
  <a href="{{ route('register') }}">register</a>
@endsection

@section('content')
  <div class="fl-card">  
    <form method="post" action="{{ url('/login') }}">
      @csrf

      <div class="row">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" placeholder="例: test@example.com"
               value="{{ old('email') }}" autocomplete="email">
        @error('email') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" placeholder="例: coachtech1106"
               autocomplete="current-password">
        @error('password') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">ログイン</button>
      </div>
    </form>
  </div>
@endsection
