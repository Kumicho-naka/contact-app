{{-- resources/views/contact/confirm.blade.php --}}
@extends('layouts.app')

@section('title', 'Confirm | FashionablyLate')
@section('page-title', 'Confirm')
@php($hideHeaderRight = true)

@section('css')
  <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<form method="post">
  @csrf

  <table class="confirm-table">
    <tr>
      <th>お名前</th>
      <td>
        {{ $input['last_name'] }} {{ $input['first_name'] }}
        <input type="hidden" name="last_name"  value="{{ $input['last_name'] }}">
        <input type="hidden" name="first_name" value="{{ $input['first_name'] }}">
      </td>
    </tr>

    <tr>
      <th>性別</th>
      <td>
        @if((int)$input['gender'] === 1) 男性
        @elseif((int)$input['gender'] === 2) 女性
        @else その他
        @endif
        <input type="hidden" name="gender" value="{{ $input['gender'] }}">
      </td>
    </tr>

    <tr>
      <th>メールアドレス</th>
      <td>
        {{ $input['email'] }}
        <input type="hidden" name="email" value="{{ $input['email'] }}">
      </td>
    </tr>

    <tr>
      <th>電話番号</th>
      <td>
        <input type="hidden" name="tel1" value="{{ $input['tel1'] }}">
        <input type="hidden" name="tel2" value="{{ $input['tel2'] }}">
        <input type="hidden" name="tel3" value="{{ $input['tel3'] }}">
      </td>
    </tr>

    <tr>
      <th>住所</th>
      <td>
        {{ $input['address'] }}
        <input type="hidden" name="address" value="{{ $input['address'] }}">
      </td>
    </tr>

    <tr>
      <th>建物名</th>
      <td>
        {{ $input['building'] }}
        <input type="hidden" name="building" value="{{ $input['building'] }}">
      </td>
    </tr>

    <tr>
      <th>お問い合わせの種類</th>
      <td>
        {{ $category?->content }}
        <input type="hidden" name="category_id" value="{{ $input['category_id'] }}">
      </td>
    </tr>

    <tr>
      <th>お問い合わせ内容</th>
      <td>
        <input type="hidden" name="detail" value="{{ $input['detail'] }}">
      </td>
    </tr>
  </table>

  <div class="actions">
    <button type="submit"
            formaction="{{ route('contact.store') }}"
            class="btn btn-primary">送信</button>

    <button type="submit"
            formaction="{{ route('contact.confirm') }}"
            name="action" value="back"
            class="btn btn-link">修正</button>
  </div>
</form>
@endsection
