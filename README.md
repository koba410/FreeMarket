# FreeMarket

このアプリケーションは、ユーザーが商品を出品、購入できるフリーマーケットプラットフォームです。主な機能として、商品出品、購入、支払い方法の選択、ユーザープロフィール管理、コメント機能、商品のお気に入り機能などを提供します。

## 特徴

- **商品出品**
  - カテゴリを複数選択可能。
  - 商品状態（ステータス）と画像のアップロードが必須。
  - 出品時に入力した情報（タイトル、説明、価格、カテゴリなど）を保存。
  
- **購入機能**
  - 商品購入時に配送先住所と支払い方法を入力。
  - Stripe を使用したクレジットカード決済とコンビニ支払いをサポート。
  
- **ユーザープロフィール管理**
  - プロフィール情報（画像、ユーザー名、住所など）の変更。
  - 出品した商品一覧と購入した商品一覧の確認。
  
- **その他**
  - 商品へのコメント機能。
  - 商品のお気に入り登録。
  - 検索機能を利用して特定のアイテムを検索可能。

---

## インストール

以下の手順に従って、このアプリケーションをローカル環境にセットアップします。

### 必要条件

- PHP 8.1 以上
- Composer
- MySQL 8.0 以上
- Node.js 16.x 以上
- Laravel 9.x
- Stripe アカウント

### 手順

1. **リポジトリをクローン**
   ```bash
   git clone https://github.com/your-repo-url/freemarket-app.git
   cd freemarket-app
   ```

2. **依存関係をインストール**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **環境ファイルを設定**
   `.env.example` をコピーして `.env` ファイルを作成し、以下の設定を更新します。
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=freemarket_db
   DB_USERNAME=root
   DB_PASSWORD=yourpassword

   STRIPE_KEY=your_stripe_key
   STRIPE_SECRET=your_stripe_secret
   ```

4. **アプリケーションキーを生成**
   ```bash
   php artisan key:generate
   ```

5. **データベースのマイグレーションとシーディング**
   ```bash
   php artisan migrate --seed
   ```

6. **サーバーを起動**
   ```bash
   php artisan serve
   ```

7. **ブラウザでアクセス**
   デフォルトでは、以下の URL にアクセスできます。
   ```
   http://127.0.0.1:8000
   ```

---

## 使用方法

### ユーザー機能

1. **登録とログイン**
   - ユーザーは登録後にプロフィールを編集可能。
   
2. **商品出品**
   - 「出品」ボタンから商品情報を入力し、出品できます。

3. **商品購入**
   - 購入ボタンをクリックし、配送先情報と支払い方法を選択。

4. **コメント機能**
   - 商品詳細ページでコメントを投稿可能。

5. **お気に入り**
   - 商品をお気に入りに追加して、後から確認できます。

---

## テスト

アプリケーションには、各機能のテストコードが含まれています。

### テスト実行方法

1. **データベースのテスト環境を設定**
   `.env.testing` ファイルを作成し、以下を設定します。
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=freemarket_test_db
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

2. **テスト実行**
   ```bash
   php artisan test
   ```

---

## 主な機能一覧

| 機能                     | 概要                                                                                     |
|--------------------------|------------------------------------------------------------------------------------------|
| ユーザー登録とログイン       | ユーザーはアカウントを作成し、ログインできます。                                                     |
| 商品出品                  | タイトル、説明、価格、カテゴリ、商品状態、画像を入力して商品を出品可能。                                     |
| 購入機能                  | 商品購入時に配送先と支払い方法を入力。Stripe を用いたクレジットカードとコンビニ支払いをサポート。                |
| コメント機能               | 商品に対してコメントを投稿可能。                                                                 |
| お気に入り機能             | 商品をお気に入りに登録し、後で簡単にアクセス可能。                                                       |
| プロフィール管理           | ユーザー名、プロフィール画像、住所などを編集可能。                                                     |
| 検索機能                 | 特定のアイテムをキーワードで検索可能。                                                             |

---

## デプロイ

本番環境にデプロイするには、以下を参考にしてください。

1. **依存関係のインストール**
   ```
   composer install --optimize-autoloader --no-dev
   npm install && npm run production
   ```

2. **環境ファイルの設定**
   `.env` ファイルを正しく設定し、Stripe キーなどの秘密情報を追加。

3. **マイグレーション**
   ```bash
   php artisan migrate --force
   ```

4. **キャッシュをクリア**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## ライセンス

このプロジェクトは [MIT License](https://opensource.org/licenses/MIT) のもとで公開されています。

---

## 貢献

改善提案やバグ報告は [GitHub Issues](https://github.com/your-repo-url/issues) にて受け付けています。Pull Request も歓迎します。

--- 

必要に応じて、この README をカスタマイズしてプロジェクトに適した内容を追加してください。