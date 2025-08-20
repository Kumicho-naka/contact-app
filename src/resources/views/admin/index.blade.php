{{-- resources/views/admin/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin | FashionablyLate')
@section('page-title', 'Admin')

@section('header-right')
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-muted">logout</button>
  </form>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
  {{-- 検索フォーム（GET） --}}
  <form method="get" action="{{ route('admin.index') }}" class="adm-form">
    <div class="adm-row adm-row--top">
      <input
        type="text"
        name="name"
        class="adm-input adm-input--name"
        placeholder="お名前やメールアドレスを入力してください"
        value="{{ request('name') }}"
      >

      <select name="gender" class="adm-select">
        <option value="">性別</option>
        <option value="1" {{ request('gender')==='1' ? 'selected' : '' }}>男性</option>
        <option value="2" {{ request('gender')==='2' ? 'selected' : '' }}>女性</option>
        <option value="3" {{ request('gender')==='3' ? 'selected' : '' }}>その他</option>
      </select>

      <select name="category_id" class="adm-select">
        <option value="">お問い合わせの種類</option>
        @foreach ($categories as $category)
          <option
            value="{{ $category->id }}"
            {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}
          >
            {{ $category->getRawOriginal('content') }}
          </option>
        @endforeach
      </select>

      <input
        type="date"
        name="date"
        value="{{ request('date') }}"
        class="adm-input adm-date"
        aria-label="日付"
      >

      <button type="submit" class="btn btn-primary">検索</button>
      <button type="submit" class="btn btn-reset" formaction="{{ route('admin.index') }}" formmethod="get">リセット</button>
    </div>

    <div class="adm-row adm-row--bottom">
      <div class="adm-left">
        <button type="submit" class="btn btn-export" formaction="{{ route('admin.export') }}" formmethod="get">エクスポート</button>
      </div>

      {{-- ページネーション --}}
      <div class="adm-pager">
        {{ $contacts->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </form>

  {{-- 一覧 --}}
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>お名前</th>
          <th>性別</th>
          <th>メールアドレス</th>
          <th>お問い合わせの種類</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($contacts as $c)
          @php($genderLabel = match((int)$c->gender){1=>'男性',2=>'女性',default=>'その他'})
          <tr>
            <td>{{ $c->last_name }} {{ $c->first_name }}</td>
            <td>{{ $genderLabel }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ optional($c->category)->content }}</td>
            <td class="t-right">
              <button
                type="button"
                class="btn btn-chip js-detail"
                data-id="{{ $c->id }}"
                data-name="{{ $c->last_name }}　{{ $c->first_name }}"
                data-gender="{{ $genderLabel }}"
                data-email="{{ $c->email }}"
                data-tel="{{ $c->tel }}"
                data-address="{{ $c->address }}"
                data-building="{{ $c->building }}"
                data-category="{{ optional($c->category)->content }}"
                data-detail="{{ $c->detail }}"
                data-created="{{ optional($c->created_at)->format('Y-m-d H:i') }}"
              >詳細</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="t-center">該当するデータがありません。</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- モーダル（お手本寄せ） --}}
  <div id="modal-backdrop" class="modal-backdrop" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="m-title">
      <div class="modal-header">
        <h3 id="m-title" class="modal-title">詳細</h3>
        <button class="modal-close" type="button" aria-label="閉じる">×</button>
      </div>

      <table class="modal-table plain">
        <tr><th>お名前</th><td id="m-name"></td></tr>
        <tr><th>性別</th><td id="m-gender"></td></tr>
        <tr><th>メールアドレス</th><td id="m-email"></td></tr>
        <tr><th>電話番号</th><td id="m-tel"></td></tr>
        <tr><th>住所</th><td id="m-address"></td></tr>
        <tr><th>建物名</th><td id="m-building"></td></tr>
        <tr><th>お問い合わせの種類</th><td id="m-category"></td></tr>
        <tr><th>お問い合わせ内容</th><td id="m-detail" class="keep-wrap"></td></tr>
        <tr><th>作成日時</th><td id="m-created"></td></tr>
      </table>

      <div class="modal-actions">
        <form id="m-delete-form" method="POST" action="">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
(function () {
  const backdrop = document.getElementById('modal-backdrop');

  const fill = (btn) => {
    document.getElementById('m-name').textContent     = btn.dataset.name     || '';
    document.getElementById('m-gender').textContent   = btn.dataset.gender   || '';
    document.getElementById('m-email').textContent    = btn.dataset.email    || '';
    document.getElementById('m-tel').textContent      = btn.dataset.tel      || '';
    document.getElementById('m-address').textContent  = btn.dataset.address  || '';
    document.getElementById('m-building').textContent = btn.dataset.building || '';
    document.getElementById('m-category').textContent = btn.dataset.category || '';
    document.getElementById('m-detail').textContent   = btn.dataset.detail   || '';
    document.getElementById('m-created').textContent  = btn.dataset.created  || '';
    document.getElementById('m-delete-form').action =
      "{{ route('admin.destroy', ':id') }}".replace(':id', btn.dataset.id);
  };

  const open = (btn) => {
    fill(btn);
    backdrop.style.display = 'flex';
    backdrop.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  };

  const close = () => {
    backdrop.style.display = 'none';
    backdrop.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  };

  document.addEventListener('click', (e) => {
    const d = e.target.closest('.js-detail');
    if (d) { e.preventDefault(); open(d); }
    if (e.target.classList.contains('modal-close') || e.target === backdrop) close();
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
})();
</script>
@endpush
