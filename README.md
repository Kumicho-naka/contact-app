# お問い合わせフォーム
課題で作成したシンプルな問い合わせフォーム（入力　→　確認　→　完了）と、認証保護された管理画面を含むアプリケーションです。

## 環境構築
### Docker ビルド
1. git clone git@github.com:Kumicho-naka/contact-app.git
2. docker compose up -d --build

### Laravel 環境構築
1. docker compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed
7. **(重要) パーミッション修正** exit で一旦抜けて、ホストから次を実行：
   docker compose exec php bash -lc '\
     mkdir -p storage/logs storage/framework/{cache,sessions,testing,views} && \
     touch storage/logs/laravel.log && \
     chown -R www-data:www-data storage bootstrap/cache && \
     find storage bootstrap/cache -type d -exec chmod 775 {} \; && \
     find storage bootstrap/cache -type f -exec chmod 664 {} \; && \
     php artisan optimize:clear'
8. ブラウザで http://localhost/ にアクセス ※必ず7.を実行してから

## 使用技術 (実行環境)
- PHP 8.1.33
- Laravel 8.83.29
- Nginx 1.21.1
- MySQL 8.0.26
- Docker / docker-compose
- 認証: Laravel Fortify 1.19.1

## テスト
- PHPUnit
- テストDB: SQLite (pdo_sqlite / sqlite3 有効)

## 主要フロー
- お問い合わせフォーム入力ページ.../
- お問い合わせフォーム確認ページ.../confirm
- サンクスページ.../thanks
- 管理画面.../admin
- ユーザ登録ページ.../register
- ログインページ.../login

## ER図
![ER図](./docs/erd.png)

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/