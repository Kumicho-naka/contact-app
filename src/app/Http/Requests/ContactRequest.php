<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーション前処理
     * tel1/tel2/tel3を結合して tel に格納
     */

    protected function prepareForValidation(): void
    {
        $tel1 = (string) $this->input('tel1', '');
        $tel2 = (string) $this->input('tel2', '');
        $tel3 = (string) $this->input('tel3', '');

        $this->merge([
            'tel' => $tel1 . $tel2 . $tel3,
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'gender'      => ['required', 'in:1,2,3'],

            'email'       => ['required', 'email', 'max:255'],

            // 各欄5桁までの半角数字
            'tel1'        => ['required', 'regex:/^\d{1,5}$/'],
            'tel2'        => ['required', 'regex:/^\d{1,5}$/'],
            'tel3'        => ['required', 'regex:/^\d{1,5}$/'],

            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],

            'category_id' => [
                'required',
                'integer',
                Rule::notIn(['0', '']),
                'exists:categories,id',
            ],

            'detail'      => ['required', 'string', 'max:120'],
        ];
    }

     public function messages(): array
    {
        return [
            'last_name.required'   => '姓を入力してください',
            'first_name.required'  => '名を入力してください',

            'gender.required'      => '性別を選択してください',
            'gender.in'            => '性別を選択してください',

            'email.required'       => 'メールアドレスを入力してください',
            'email.email'          => 'メールアドレスはメール形式で入力してください',

            'tel1.required'        => '電話番号を入力してください',
            'tel2.required'        => '電話番号を入力してください',
            'tel3.required'        => '電話番号を入力してください',
            'tel1.regex'           => '電話番号は5桁までの数字で入力してください',
            'tel2.regex'           => '電話番号は5桁までの数字で入力してください',
            'tel3.regex'           => '電話番号は5桁までの数字で入力してください',

            'address.required'     => '住所を入力してください',

            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.not_in'   => 'お問い合わせの種類を選択してください',
            'category_id.exists'   => 'お問い合わせの種類を選択してください',

            'detail.required'      => 'お問い合わせ内容を入力してください',
            'detail.max'           => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}
