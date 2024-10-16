# FreeMarket

## 環境構築

### Dockerビルド
1. `git clone git@github.com:koba410/secondtest.git`
2. `docker-compose up -d --build`

＊ MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせて`docker-compose.yml`ファイルを編集してください。

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. `.env.example`ファイルから`.env`を作成し、環境変数を変更
4. `php artisan key:generate`
5. `php artisan migrate:refresh`
6. `php artisan db:seed`
7. `php artisan storage:link`

## 使用技術
- PHP 7.4.9
- Laravel 8.83.27
- MySQL 8.0.26
- niginx 1.21.1

## URL
- 開発環境 : [http://localhost](http://localhost/)
- phpMyAdmin : [http://localhost:8080/](http://localhost:8080/)
