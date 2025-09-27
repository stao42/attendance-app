# CoachTech フリマアプリケーション

## 概要
CoachTechのフリマアプリケーションです。ユーザーは商品の出品、購入、いいね機能などを利用できます。

## 機能
- ユーザー認証（ログイン・会員登録・ログアウト）
- 商品一覧表示・検索
- 商品詳細表示
- 商品出品
- 商品購入
- いいね機能（マイリスト）
- コメント機能
- プロフィール管理

## 技術スタック
- **フレームワーク**: Laravel 12
- **データベース**: SQLite
- **フロントエンド**: HTML/CSS/JavaScript
- **認証**: Laravel標準認証

## インストール・セットアップ

### 1. 依存関係のインストール
```bash
composer install
```

### 2. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

### 3. データベース設定
```bash
php artisan migrate
php artisan db:seed
```

### 4. ストレージリンク作成
```bash
php artisan storage:link
```

### 5. サーバー起動
```bash
php artisan serve
```
### Docker での起動
Docker を利用する場合は以下の手順でコンテナを起動できます。

```bash
docker compose build app
docker compose up app
```

初回起動時はコンテナ内で `.env` のコピー、`composer install`、`php artisan migrate` まで自動実行されます。起動後は `http://localhost:8000` にアクセスしてください。

フロントエンド開発で Vite の開発サーバーも利用する場合は別ターミナルで次を実行します。

```bash
docker compose run --rm vite npm install
docker compose up vite
```

Laravel コンテナで Artisan コマンドや Composer の更新を行いたい場合は以下のように `docker compose run` を利用できます。

```bash
docker compose run --rm app php artisan migrate
WWWUSER=$(id -u) WWWGROUP=$(id -g) docker compose run --rm app composer install
```

権限の警告が出る環境では `WWWUSER` と `WWWGROUP` を指定して起動してください。

```bash
WWWUSER=$(id -u) WWWGROUP=$(id -g) docker compose up app
```

`RUN_MIGRATIONS=0` を指定するとコンテナ起動時の自動マイグレーションをスキップできます。


## 画面構成

| 画面ID | 画面名称 | パス | 説明 |
|--------|----------|------|------|
| PG01 | 商品一覧画面（トップ画面） | `/` | 全商品の一覧表示 |
| PG02 | 商品一覧画面（トップ画面）_マイリスト | `/?tab=mylist` | いいねした商品の一覧 |
| PG03 | 会員登録画面 | `/register` | 新規ユーザー登録 |
| PG04 | ログイン画面 | `/login` | ユーザーログイン |
| PG05 | 商品詳細画面 | `/item/{item_id}` | 商品の詳細情報 |
| PG06 | 商品購入画面 | `/purchase/{item_id}` | 商品購入手続き |
| PG07 | 送付先住所変更画面 | `/purchase/address/{item_id}` | 配送先住所変更 |
| PG08 | 商品出品画面 | `/sell` | 商品出品 |
| PG09 | プロフィール画面 | `/mypage` | ユーザープロフィール |
| PG10 | プロフィール編集画面 | `/mypage/profile` | プロフィール編集 |
| PG11 | プロフィール画面_購入した商品一覧 | `/mypage?page=buy` | 購入履歴 |
| PG12 | プロフィール画面_出品した商品一覧 | `/mypage?page=sell` | 出品履歴 |

## データベース構成

### テーブル一覧
- `users` - ユーザー情報
- `products` - 商品情報
- `categories` - カテゴリ情報
- `comments` - コメント情報
- `purchases` - 購入情報
- `favorites` - いいね情報

## 開発者向け情報

### ルート一覧
```bash
php artisan route:list
```

### マイグレーション実行
```bash
php artisan migrate
```

### シーダー実行
```bash
php artisan db:seed
```

## ライセンス
このプロジェクトはCoachTechの学習用プロジェクトです。
