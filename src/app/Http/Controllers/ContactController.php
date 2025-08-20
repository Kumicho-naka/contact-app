<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ContactController extends Controller
{
    /**
     * 入力ページ表示(GET /)
     */
    public function create(): View
    {
        // セレクトボックス用：先頭に「選択してください」（value="0" 等）はBlade側で追加
        $categories = Category::orderBy('id')->get();

        return view('contact.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * 確認ページ表示（POST /confirm）
     * バリデーションは ContactRequest で実行。
     */
    public function confirm(ContactRequest $request): View|RedirectResponse
    {
        // 確認画面から「修正」で戻る場合の分岐（name="action" value="back" を想定）
        if($request->input('action') === 'back'){
            return redirect()->route('contact.create')->withInput();
        }

        // validated() には tel1/tel2/tel3 と、prepareForValidation()で結合済みのtelが含まれる
        $input = $request->validated();

        // 表示用にカテゴリ名を取得
        $category = Category::find($input['category_id']);

        return view('contact.confirm',[
            'input'     => $input,      // 例：$input['last_name'], $input['tel']など
            'category'  => $category,   // 例：$category?->content
        ]);
    }

    /**
     * 保存処理（POST \store）
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Contact::create([
            'last_name'   => $data['last_name'],
            'first_name'  => $data['first_name'],
            'gender'      => (int) $data['gender'],
            'email'       => $data['email'],
            'tel'         => $data['tel1'] . $data['tel2'] . $data['tel3'], // ← ここで結合
            'address'     => $data['address'],
            'building'    => $data['building'] ?? null,
            'category_id' => (int) $data['category_id'],
            'detail'      => $data['detail'],
        ]);

        return redirect()->route('contact.thanks');
    }


    /**
     * サンクスページ（GET /thanks）
     */
    public function thanks(): View
    {
        return view('contact.thanks');
    }
}
