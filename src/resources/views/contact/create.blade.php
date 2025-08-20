{{-- resources/views/contact/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Contact | FashionablyLate')
@section('page-title', 'Contact')

@php($hideHeaderRight = true)

@section('css')
  <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<form action="{{ route('contact.confirm') }}" method="post">
  @csrf

  {{-- お名前（姓・名） --}}
  <div class="row">
    <label for="last_name">お名前<span class="req">※</span></label>
    <div class="field">
      <div class="inline">
        <input type="text" id="last_name" name="last_name"
               placeholder="例:山田"
               value="{{ old('last_name', $input['last_name'] ?? '') }}">
        <input type="text" id="first_name" name="first_name"
               placeholder="例:太郎"
               value="{{ old('first_name', $input['first_name'] ?? '') }}">
      </div>
      @error('last_name')  <div class="err">{{ $message }}</div> @enderror
      @error('first_name') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- 性別（デフォルト：男性） --}}
  <div class="row">
    <label>性別<span class="req">※</span></label>
    <div class="field">
      <div class="radio-group">
        <label><input type="radio" name="gender" value="1"
          {{ old('gender', $input['gender'] ?? '1') == '1' ? 'checked' : '' }}> 男性</label>
        <label><input type="radio" name="gender" value="2"
          {{ old('gender', $input['gender'] ?? '') == '2' ? 'checked' : '' }}> 女性</label>
        <label><input type="radio" name="gender" value="3"
          {{ old('gender', $input['gender'] ?? '') == '3' ? 'checked' : '' }}> その他</label>
      </div>
      @error('gender') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- メールアドレス --}}
  <div class="row">
    <label for="email">メールアドレス<span class="req">※</span></label>
    <div class="field">
      <input type="email" id="email" name="email"
             placeholder="例:test@example.com"
             value="{{ old('email', $input['email'] ?? '') }}">
      @error('email') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- 電話番号（3分割） --}}
  <div class="row">
    <label>電話番号<span class="req">※</span></label>
    <div class="field">
      <div class="tel">
        <input type="text" name="tel1" maxlength="5"
               placeholder="080"
               value="{{ old('tel1', $input['tel1'] ?? '') }}">
        <span class="sep">-</span>
        <input type="text" name="tel2" maxlength="5"
               placeholder="1234"
               value="{{ old('tel2', $input['tel2'] ?? '') }}">
        <span class="sep">-</span>
        <input type="text" name="tel3" maxlength="5"
               placeholder="5678"
               value="{{ old('tel3', $input['tel3'] ?? '') }}">
      </div>
      @php($telError = $errors->first('tel1') ?: ($errors->first('tel2') ?: $errors->first('tel3')))
      @if($telError) <div class="err">{{ $telError }}</div> @endif
    </div>
  </div>

  {{-- 住所 --}}
  <div class="row">
    <label for="address">住所<span class="req">※</span></label>
    <div class="field">
      <input type="text" id="address" name="address"
             placeholder="例:東京都渋谷区千駄ヶ谷1-2-3"
             value="{{ old('address', $input['address'] ?? '') }}">
      @error('address') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- 建物名（任意） --}}
  <div class="row">
    <label for="building">建物名</label>
    <div class="field">
      <input type="text" id="building" name="building"
             placeholder="例:千駄ヶ谷マンション101"
             value="{{ old('building', $input['building'] ?? '') }}">
      @error('building') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- お問い合わせの種類 --}}
  <div class="row">
    <label for="category_id">お問い合わせの種類<span class="req">※</span></label>
    <div class="field">
      <select id="category_id" name="category_id">
        <option value="">選択してください</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}"
            {{ old('category_id', $input['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
            {{ $category->content }}
          </option>
        @endforeach
      </select>
      @error('category_id') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- お問い合わせ内容（120文字以内） --}}
  <div class="row">
    <label for="detail">お問い合わせ内容<span class="req">※</span></label>
    <div class="field">
      <textarea id="detail" name="detail" maxlength="120"
                placeholder="お問い合わせ内容をご記載ください">{{ old('detail', $input['detail'] ?? '') }}</textarea>
      @error('detail') <div class="err">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- 送信ボタン --}}
  <div class="actions">
    <button type="submit" class="btn btn-primary">確認画面</button>
  </div>
</form>
@endsection
