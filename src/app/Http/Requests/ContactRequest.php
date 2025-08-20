<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $norm = fn($v) => preg_replace('/\s+/u', '', mb_convert_kana((string)$v, 'n'));

        $tel1 = $norm($this->input('tel1', ''));
        $tel2 = $norm($this->input('tel2', ''));
        $tel3 = $norm($this->input('tel3', ''));

        $this->merge([
            'tel1' => $tel1,
            'tel2' => $tel2,
            'tel3' => $tel3,
            'tel'  => $tel1 . $tel2 . $tel3, // 確認画面や保存で使いたければ利用可
        ]);
    }

    public function rules(): array
    {
        return [
            'last_name'   => ['bail','required','string','max:255'],
            'first_name'  => ['bail','required','string','max:255'],
            'gender'      => ['bail','required','in:1,2,3'],

            'email'       => ['bail','required','email','max:255'],

            // 電話は各欄 1〜5 桁の半角数字
            'tel1'        => ['bail','required','regex:/^\d{1,5}$/'],
            'tel2'        => ['bail','required','regex:/^\d{1,5}$/'],
            'tel3'        => ['bail','required','regex:/^\d{1,5}$/'],

            'address'     => ['bail','required','string','max:255'],
            'building'    => ['nullable','string','max:255'],

            'category_id' => ['bail','required','integer', Rule::notIn(['0','']), 'exists:categories,id'],

            'detail'      => ['bail','required','string','max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            // 氏名
            'last_name.required'   => '姓を入力してください',
            'first_name.required'  => '名を入力してください',

            // 性別
            'gender.required'      => '性別を選択してください',
            'gender.in'            => '性別を選択してください',

            // メール
            'email.required'       => 'メールアドレスを入力してください',
            'email.email'          => 'メールアドレスはメール形式で入力してください',

            // 電話（未入力・形式）
            'tel1.required'        => '電話番号を入力してください',
            'tel2.required'        => '電話番号を入力してください',
            'tel3.required'        => '電話番号を入力してください',
            'tel1.regex'           => '電話番号は5桁までの数字で入力してください',
            'tel2.regex'           => '電話番号は5桁までの数字で入力してください',
            'tel3.regex'           => '電話番号は5桁までの数字で入力してください',

            // 住所
            'address.required'     => '住所を入力してください',

            // 種類
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.not_in'   => 'お問い合わせの種類を選択してください',
            'category_id.exists'   => 'お問い合わせの種類を選択してください',

            // 内容
            'detail.required'      => 'お問い合わせ内容を入力してください',
            'detail.max'           => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}
