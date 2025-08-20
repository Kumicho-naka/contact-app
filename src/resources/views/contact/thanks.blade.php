{{-- resources/views/contact/thanks.blade.php --}}
@extends('layouts.app')

@php($hideHeader = true) 
@php($hideTitle  = true) 

@section('title', 'Thanks | FashionablyLate')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
  <div class="thanks">
    <div class="thanks__bg">Thank you</div>
    <div class="thanks__panel">
      <p class="thanks__message">お問い合わせありがとうございました</p>
      <a href="{{ route('contact.create') }}" class="btn btn-primary">HOME</a>
    </div>
  </div>
@endsection
