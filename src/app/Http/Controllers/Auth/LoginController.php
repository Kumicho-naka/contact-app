<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

class LoginController extends Controller
{
    // GET /login 用（画面表示）
    public function show()
    {
        return view('auth.login');
    }

    // POST /login 用（FormRequestで検証→認証→遷移）
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, false)) {
            // セッション固定攻撃対策
            $request->session()->regenerate();
            // 直前にアクセスを試みたURLがあればそこへ、なければ /admin
            return redirect()->intended('/admin');
        }

        // 認証失敗時：フィールド下に出るよう email に束ねて返す
        return back()
            ->withErrors(['email' => '認証に失敗しました'])
            ->withInput($request->except('password'));
    }

    // POST /logout 用
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
