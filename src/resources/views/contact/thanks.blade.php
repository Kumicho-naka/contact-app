{{-- resources/views/contact/thanks.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Thanks | FashionablyLate</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- サンクス専用CSS（別途作成：public/css/thanks.css など） --}}
  <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
</head>
<body>
  <main class="thanks">
    <div class="thanks__bg">Thank you</div>
    <div class="thanks__panel">
      <p class="thanks__message">お問い合わせありがとうございました</p>
      <a href="{{ route('contact.create') }}" class="btn btn-primary">HOME</a>
    </div>
  </main>
</body>
</html>