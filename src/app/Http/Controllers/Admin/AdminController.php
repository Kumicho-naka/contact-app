<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        [$query, $exact] = $this->buildQuery($request);

        // 7件ごとにページネート（クエリ文字列維持）
        $contacts   = $query->latest('created_at')->paginate(7)->withQueryString();
        $categories = Category::orderBy('id')->get(['id','content']);

        return view('admin.index', compact('contacts', 'categories'));
    }

    public function export(Request $request): StreamedResponse
    {
        [$query, $exact] = $this->buildQuery($request);

        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query) {
            // UTF-8 BOM（Excel対策）
            echo chr(0xEF) . chr(0xBB) . chr(0xBF);

            $out = fopen('php://output', 'w');

            // ヘッダ
            fputcsv($out, [
                'id','姓','名','性別','メールアドレス','電話番号','住所','建物名',
                'お問い合わせの種類','お問い合わせ内容','作成日時'
            ]);

            // 条件を反映したまま出力
            $query->orderBy('id')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $c) {
                    fputcsv($out, [
                        $c->id,
                        $c->last_name,
                        $c->first_name,
                        $this->genderLabel($c->gender),
                        $c->email,
                        $c->tel,
                        $c->address,
                        $c->building,
                        optional($c->category)->content,
                        $c->detail,
                        optional($c->created_at)?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($out);
        }, 200, $headers);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('status', '削除しました');
    }

    /**
     * 検索条件ビルド
     * - name: 姓/名/フルネーム + メールも対象
     * - gender: 1/2/3
     * - category_id: 一致
     * - date: 当日(00:00〜23:59:59)
     * - match: 'partial' / 'exact'
     */
    private function buildQuery(Request $request): array
    {
        $match = strtolower((string) $request->input('match', 'partial')); // 'partial' / 'exact'
        $exact = $match === 'exact';

        $like = function (string $s): string {
            $s = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $s);
            return '%' . $s . '%';
        };

        $q = Contact::query()->with('category');

        // 名前欄：姓/名/フルネーム + メール
        if ($request->filled('name')) {
            $name = trim((string) $request->input('name'));
            $nameNoSpace = preg_replace('/\s+/u', '', $name);

            $q->where(function ($qq) use ($name, $nameNoSpace, $exact, $like) {
                if ($exact) {
                    $qq->where('last_name', $name)
                       ->orWhere('first_name', $name)
                       ->orWhereRaw("CONCAT(last_name, ' ', first_name) = ?", [$name])
                       ->orWhereRaw("REPLACE(CONCAT(last_name, first_name), ' ', '') = ?", [$nameNoSpace])
                       ->orWhere('email', $name);
                } else {
                    $kw = $like($name);
                    $kwNoSpace = $like($nameNoSpace);

                    $qq->where('last_name', 'LIKE', $kw)
                       ->orWhere('first_name', 'LIKE', $kw)
                       ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", [$kw])
                       ->orWhereRaw("REPLACE(CONCAT(last_name, first_name), ' ', '') LIKE ?", [$kwNoSpace])
                       ->orWhere('email', 'LIKE', $kw);
                }
            });
        }

        // 性別
        if (in_array($request->input('gender'), ['1','2','3'], true)) {
            $q->where('gender', (int)$request->input('gender'));
        }

        // 種別
        if ($request->filled('category_id')) {
            $q->where('category_id', (int)$request->input('category_id'));
        }

        // 日付（1つ）：当日範囲
        if ($request->filled('date')) {
            $d = $request->input('date');
            $q->whereBetween('created_at', [
                Carbon::parse($d)->startOfDay(),
                Carbon::parse($d)->endOfDay(),
            ]);
        }

        return [$q, $exact];
    }

    private function genderLabel($v): string
    {
        return match ((int)$v) {
            1       => '男性',
            2       => '女性',
            default => 'その他',
        };
    }
}
