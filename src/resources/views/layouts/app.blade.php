<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'FashionablyLate')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

@php
  // 認証系（login / register）のみベージュ背景にする
  $isAuthPage = request()->routeIs('login') || request()->routeIs('register');
@endphp

<body class="{{ $isAuthPage ? 'bg-auth' : 'bg-default' }}">
  @empty($hideHeader)  {{-- $hideHeader がセットされていない時だけヘッダー表示（thanks などで非表示にできる） --}}
    <header class="fl-header">
      <div class="fl-header__inner">
        <a href="{{ url('/') }}" class="fl-header__brand">FashionablyLate</a>

        <div class="fl-header__right">
          @empty($hideHeaderRight)
            @guest
              @if (request()->routeIs('register'))
                <a href="{{ route('login') }}">login</a>
              @elseif (request()->routeIs('login'))
                <a href="{{ route('register') }}">register</a>
              @endif
            @endguest

            @auth
              <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link">logout</button>
              </form>
            @endauth
          @endempty
        </div>
      </div>
    </header>
  @endempty

  <main class="fl-main">
    @empty($hideTitle)
      <div class="fl-title">@yield('page-title')</div>
    @endempty

    <div class="fl-container">
      @yield('content')
    </div>
  </main>

  @stack('scripts')
</body>
</html>
