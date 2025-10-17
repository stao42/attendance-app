# CoachTech フリマアプリケーション

## 概要
CoachTechのフリマアプリケーションです。ユーザーは商品の出品、購入、いいね機能などを利用できます。

## 機能
- **ユーザー認証**: ログイン・会員登録・ログアウト・メール認証
- **商品管理**: 商品一覧表示・検索・詳細表示・出品・編集・削除
- **購入機能**: 商品購入・決済（カード/コンビニ払い）・購入履歴
- **いいね機能**: マイリスト（お気に入り商品の管理）
- **コメント機能**: 商品へのコメント投稿・表示
- **プロフィール管理**: プロフィール編集・画像アップロード・住所管理
- **メール機能**: 会員登録時のメール認証（開発環境ではMailhog使用）

## 技術スタック
- **フレームワーク**: Laravel 11
- **データベース**: MySQL 8.0
- **フロントエンド**: HTML/CSS/JavaScript、Vite
- **認証**: Laravel Fortify（メール認証含む）
- **メール**: Mailhog（開発環境）、Laravel Mail
- **コンテナ**: Docker Compose
- **テスト**: PHPUnit

## セットアップ

### 必要環境
- Docker & Docker Compose
- Git

### 起動手順

```bash
# 1. リポジトリをクローン
git clone <repository-url>
cd coachtech

# 2. 環境設定
cp .env.example .env

# 3. 起動
docker compose up -d

# 4. アクセス
# http://localhost:8000 (アプリ)
# http://localhost:8025 (メール確認用)
```

### 開発用コマンド

```bash
# フロントエンド開発サーバー（ホットリロード）
docker compose exec app npm run dev

# データベースリセット（シーダー込み）
docker compose exec app php artisan migrate:fresh --seed

# ストレージリンク再作成
docker compose exec app php artisan storage:link

# MySQL接続確認
docker compose exec mysql mysql -u root -ppassword -e "SHOW DATABASES;"
```

### ダミーデータの登録
アプリケーションには以下のダミー商品が登録済みです：
- 腕時計（¥15,000）
- HDD（¥5,000）
- 玉ねぎ3束（¥300）
- 革靴（¥4,000）
- ノートPC（¥45,000）
- マイク（¥8,000）
- ショルダーバッグ（¥3,500）
- タンブラー（¥500）
- コーヒーミル（¥4,000）
- メイクセット（¥2,500）

### トラブルシューティング

#### よくある問題と解決方法

**1. コンテナが起動しない**
```bash
# ポートが使用中の場合は、別のポートを使用
docker compose down
docker compose up -d

# または、docker-compose.ymlでポートを変更
```

**2. データベースエラー**
```bash
# データベースをリセット
docker compose exec app php artisan migrate:fresh --seed

# MySQLコンテナの状態確認
docker compose ps mysql

# MySQLコンテナの再起動
docker compose restart mysql

# MySQL接続テスト
docker compose exec mysql mysql -u root -ppassword -e "SELECT 1;"
```

**3. ストレージリンクエラー**
```bash
# ストレージリンクを再作成
docker compose exec app php artisan storage:link

# 権限エラーの場合
docker compose exec app chmod -R 755 storage/
```

**4. メールが送信されない**
```bash
# Mailhogが起動しているか確認
docker compose ps

# Mailhogにアクセス
# http://localhost:8025
```

**5. フロントエンドアセットが読み込めない**
```bash
# アセットを再ビルド
docker compose exec app npm run build

# 開発用サーバーを起動
docker compose exec app npm run dev
```

**6. 権限エラー（macOS/Linux）**
```bash
# 権限を修正
sudo chown -R $USER:$USER .
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```


## 画面構成

| 画面ID | 画面名称 | パス | 説明 |
|--------|----------|------|------|
| PG01 | 商品一覧画面（トップ画面） | `/` | 全商品の一覧表示・検索機能 |
| PG02 | 商品一覧画面_マイリスト | `/?tab=mylist` | いいねした商品の一覧 |
| PG03 | 会員登録画面 | `/register` | 新規ユーザー登録・メール認証 |
| PG04 | ログイン画面 | `/login` | ユーザーログイン |
| PG05 | 商品詳細画面 | `/item/{product}` | 商品の詳細情報・コメント表示 |
| PG06 | 商品購入画面 | `/purchase/{product}` | 商品購入手続き・決済 |
| PG07 | 送付先住所変更画面 | `/purchase/address/{product}` | 配送先住所変更 |
| PG08 | 商品出品画面 | `/sell` | 商品出品・編集 |
| PG09 | プロフィール画面 | `/mypage` | ユーザープロフィール・出品履歴 |
| PG10 | プロフィール編集画面 | `/mypage/profile` | プロフィール編集・画像アップロード |
| PG11 | プロフィール画面_購入した商品一覧 | `/mypage?page=buy` | 購入履歴 |
| PG12 | プロフィール画面_出品した商品一覧 | `/mypage?page=sell` | 出品履歴 |

### アクセスURL
- **アプリケーション**: http://localhost:8000
- **Mailhog（メール確認）**: http://localhost:8025
- **MySQL**: localhost:3306

## Docker構成

### サービス一覧
- **app**: Laravelアプリケーション（PHP 8.2 + Laravel 11）
- **mysql**: MySQL 8.0データベース
- **vite**: フロントエンド開発サーバー（Node.js 20）
- **mailhog**: メール開発サーバー

### データ永続化
- **MySQLデータ**: Dockerボリューム `coachtech_mysql_data`
- **アプリケーションファイル**: ホストマシンと同期

### 環境変数
```bash
# アプリケーション設定
APP_ENV=local
APP_DEBUG=true
RUN_MIGRATIONS=1

# データベース設定
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coachtech
DB_USERNAME=root
DB_PASSWORD=password

# メール設定
MAIL_HOST=mailhog
MAIL_PORT=1025
```

## データベース構成

### データベース情報
- **種類**: MySQL 8.0
- **データベース名**: coachtech
- **ユーザー**: root / coachtech
- **パスワード**: password
- **ポート**: 3306

### テーブル一覧
- `users` - ユーザー情報（プロフィール画像、住所含む）
- `products` - 商品情報（画像、価格、状態、カテゴリ含む）
- `categories` - カテゴリ情報
- `comments` - コメント情報（商品へのコメント）
- `purchases` - 購入情報（決済方法、配送先、ステータス含む）
- `favorites` - いいね情報（マイリスト機能）
- `migrations` - マイグレーション履歴
- `cache` - キャッシュデータ
- `sessions` - セッションデータ
- `jobs` - ジョブキュー
- `failed_jobs` - 失敗したジョブ

## 開発者向け情報

### コマンド一覧
```bash
# ルート一覧表示
docker compose exec app php artisan route:list

# マイグレーション実行
docker compose exec app php artisan migrate

# データベースリセット（シーダー込み）
docker compose exec app php artisan migrate:fresh --seed

# ストレージリンク作成
docker compose exec app php artisan storage:link

# キャッシュクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# MySQLデータベース確認
docker compose exec mysql mysql -u root -ppassword -e "USE coachtech; SHOW TABLES;"

# MySQL接続テスト
docker compose exec mysql mysql -u root -ppassword -e "SELECT COUNT(*) FROM products;"
```

### テスト実行
```bash
# 全テスト実行
docker compose exec app php artisan test

# 特定のテスト実行
docker compose exec app php artisan test --filter=RegistrationTest

# コードスタイルチェック
docker compose exec app ./vendor/bin/pint --test

# コードスタイル修正
docker compose exec app ./vendor/bin/pint
```

### 開発用コマンド
```bash
# Tinker（対話式シェル）
docker compose exec app php artisan tinker

# ログ確認
docker compose exec app tail -f storage/logs/laravel.log

# Composer依存関係更新
docker compose exec app composer update

# NPM依存関係更新
docker compose exec app npm update

# MySQLデータベースバックアップ
docker compose exec mysql mysqldump -u root -ppassword coachtech > backup.sql

# MySQLデータベースリストア
docker compose exec -i mysql mysql -u root -ppassword coachtech < backup.sql
```

### 主要な機能実装

#### 認証機能
- Laravel Fortifyを使用した認証システム
- メール認証機能（開発環境ではMailhog）
- プロフィール画像アップロード機能

#### 商品管理
- 商品の出品・編集・削除
- 画像アップロード機能
- カテゴリ管理
- 商品状態管理（良好、目立った傷や汚れなし、やや傷や汚れあり、状態が悪い）

#### 購入機能
- カード決済・コンビニ払い対応
- 配送先住所管理
- 購入履歴管理

#### いいね機能
- マイリスト機能
- お気に入り商品の管理

#### コメント機能
- 商品へのコメント投稿
- ユーザープロフィール画像表示

## 更新履歴

### v2.0.0 (2025-10-18)
- **データベース**: SQLite → MySQL 8.0 に移行
- **Docker構成**: MySQLサービスを追加
- **開発環境**: より本格的な開発環境に改善
- **README**: MySQL対応のドキュメント更新

### v1.0.0 (2025-09-17)
- **初回リリース**: 基本的なフリマアプリケーション機能
- **認証**: Laravel Fortifyによる認証システム
- **商品管理**: 出品・購入・いいね機能
- **プロフィール**: 画像アップロード・住所管理

## ライセンス
このプロジェクトはCoachTechの学習用プロジェクトです。
