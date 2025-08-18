<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'FashionablyLate')</title>

  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}"> 

  @yield('css')
</head>
<body>
  <header class="fl-header">
    <div class="fl-header__inner">
      <a href="{{ url('/') }}" class="fl-header__brand">FashionablyLate</a>
      <div class="fl-header__right">
        @yield('header-right')
      </div>
    </div>
  </header>

  <main class="fl-main">
    <div class="fl-title">@yield('page-title')</div>
    <div class="fl-container">
      @yield('content')
    </div>
  </main>
</body>
</html>