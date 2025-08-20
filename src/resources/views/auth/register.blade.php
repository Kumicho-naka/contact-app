@extends('layouts.app')

@section('title', 'Register | FashionablyLate')
@section('page-title', 'Register')

@section('header-right')
  <a href="{{ route('login') }}">login</a>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
  <div class="fl-card">  
    <form method="post" action="{{ url('/register') }}">
      @csrf

      <div class="row">
        <label for="name">お名前</label>
        <input id="name" type="text" name="name" placeholder="例: 山田 太郎" value="{{ old('name') }}">
        @error('name') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" placeholder="例: test@example.com" value="{{ old('email') }}">
        @error('email') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" placeholder="例: coachtech1106">
        @error('password') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">登録</button>
      </div>
    </form>
  </div>
@endsection
