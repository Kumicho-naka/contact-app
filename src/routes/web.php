<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// お問い合わせ（ユーザー側）
Route::get('/', [ContactController::class, 'create']);          // 入力
Route::post('/confirm', [ContactController::class, 'confirm']); // 確認

// ※保存用の内部ルート(これがないとthanksに行かない)
Route::post('/store', [ContactController::class, 'store']);     // 保存 → /thanks にリダイレクト
Route::get('/thanks', [ContactController::class, 'thanks']);    // サンクスページ

// 管理画面
Route::get('/admin', [ContactAdminController::class, 'index']);

// 認証
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);