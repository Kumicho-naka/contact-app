<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /** 未ログインは /admin へ行けず /login にリダイレクト */
    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /** ログイン成功で /admin へ */
    public function test_login_redirects_to_admin_and_can_view(): void
    {
        $user = User::query()->create([
            'name'     => 'テスト太郎',
            'email'    => 'taro@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->post('/login', [
            'email'    => 'taro@example.com',
            'password' => 'password123',
        ])->assertRedirect('/admin');

        $this->actingAs($user)->get('/admin')
            ->assertOk()
            ->assertSee('Admin');
    }

    /** ログアウトで /login に戻る */
    public function test_logout_redirects_to_login(): void
    {
        $user = User::query()->create([
            'name'     => 'テスト花子',
            'email'    => 'hanako@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $this->post('/logout')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }
}
