<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ContactFlowTest extends TestCase
{
    use RefreshDatabase;

    /** カテゴリをテスト用に投入（id固定） */
    private function seedCategories(): void
    {
        $now = now();
        DB::table('categories')->insert([
            ['id' => 1, 'content' => '商品の お届けについて', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'content' => '商品の 交換について',   'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'content' => '商品トラブル',         'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'content' => 'ショップへのお問い合わせ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'content' => 'その他',               'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /** 入力画面が表示できる */
    public function test_can_open_contact_create_page(): void
    {
        $this->seedCategories();
        $this->get('/')->assertOk()->assertSee('Contact');
    }

    /** 姓の必須エラー */
    public function test_validate_last_name_required(): void
    {
        $this->seedCategories();

        $payload = [
            // 'last_name' 省略
            'first_name'  => '太郎',
            'gender'      => 1,
            'email'       => 'test@example.com',
            'tel1'        => '080',
            'tel2'        => '1234',
            'tel3'        => '5678',
            'address'     => '東京都渋谷区',
            'building'    => '',
            'category_id' => 1,
            'detail'      => '問い合わせ本文',
        ];

        $this->from('/')->post('/confirm', $payload)
            ->assertRedirect('/')
            ->assertSessionHasErrors(['last_name']);
    }

    /** 正常系：確認→保存→サンクス、DB登録も確認 */
    public function test_contact_flow_confirm_store_thanks(): void
    {
        $this->seedCategories();

        $payload = [
            'last_name'   => '山田',
            'first_name'  => '太郎',
            'gender'      => 1,
            'email'       => 'test@example.com',
            'tel1'        => '080',
            'tel2'        => '1234',
            'tel3'        => '5678',
            'address'     => '東京都渋谷区千駄ヶ谷1-2-3',
            'building'    => '千駄ヶ谷マンション101',
            'category_id' => 2,
            'detail'      => '届いた商品が異なるため交換希望です。',
        ];

        // 確認画面（仕様：ハイフン無し表示）
        $this->post('/confirm', $payload)
            ->assertOk()
            ->assertSee('Confirm')
            ->assertSee('08012345678')
            ->assertSee('test@example.com');

        // 保存 → Thanks
        $this->post('/store', $payload)->assertRedirect('/thanks');

        // DB はハイフン無しで保存
        $this->assertDatabaseHas('contacts', [
            'last_name'  => '山田',
            'first_name' => '太郎',
            'gender'     => 1,
            'email'      => 'test@example.com',
            'tel'        => '08012345678',
            'address'    => '東京都渋谷区千駄ヶ谷1-2-3',
        ]);

        // サンクス
        $this->get('/thanks')->assertOk()->assertSee('ありがとうございました');
    }
}
