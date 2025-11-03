# CoachTech 勤怠管理アプリケーション

Laravel + Docker + MySQL で構築された勤怠管理システムです。

## 概要

- **サービス名**: CoachTech 勤怠管理アプリケーション
- **ターゲットユーザー**: 社会人全般（一般ユーザー・管理者）
- **技術スタック**:
  - PHP 8.2
  - Laravel 12.0
  - MySQL 8.0
  - Docker / Docker Compose

## 機能一覧

### 一般ユーザー向け機能

- **ユーザー認証**
  - 会員登録（バリデーション付き）
  - ログイン/ログアウト
  - エラーメッセージ表示（日本語）

- **打刻機能**
  - 出勤打刻
  - 退勤打刻
  - 休憩開始/終了
  - リアルタイムステータス表示（出勤中/休憩中/退勤済/勤務外）

- **勤怠管理**
  - 勤怠一覧表示（月別）
  - 勤怠詳細表示
  - 打刻修正申請

- **申請管理**
  - 修正申請一覧表示
  - 申請ステータス確認

### 管理者向け機能

- **認証**
  - 管理者専用ログイン
  - 管理者権限チェック

- **勤怠管理**
  - 全スタッフの勤怠一覧
  - 勤怠詳細表示・編集
  - スタッフ別勤怠一覧
  - CSVエクスポート機能

- **スタッフ管理**
  - スタッフ一覧表示

- **申請承認**
  - 打刻修正申請一覧
  - 申請承認/却下
  - 承認時のコメント機能

## 開発環境のセットアップ

### 前提条件

- Docker Desktop がインストールされていること
- Git がインストールされていること
- 最低 4GB の空きメモリ推奨

### セットアップ手順

1. **リポジトリのクローン**

```bash
git clone <repository-url>
cd coachtech
```

2. **`.env`ファイルの設定**

既存の`.env`ファイルがある場合は、以下のデータベース設定を確認してください：

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=coachtech
DB_USERNAME=root
DB_PASSWORD=root
```

3. **Dockerコンテナのビルドと起動**

```bash
docker compose up -d --build
```

初回起動時はビルドに時間がかかります。

4. **Composerパッケージのインストール**

```bash
docker compose exec app composer install
```

5. **アプリケーションキーの生成**

```bash
docker compose exec app php artisan key:generate
```

6. **データベースのマイグレーション**

```bash
docker compose exec app php artisan migrate
```

7. **シーダーの実行（テストデータ作成）**

```bash
docker compose exec app php artisan db:seed
```

シーダー実行後、以下のユーザーでログインできます：

- **管理者ユーザー**
  - Email: `admin@example.com`
  - Password: `password`
  - 権限: 管理者（すべての機能にアクセス可能）

- **テストユーザー**
  - Email: `test@example.com`
  - Password: `password`
  - 権限: 一般ユーザー

### アクセス

- **アプリケーション**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080

## 開発

### コンテナ内でのコマンド実行

```bash
# Composer
docker compose exec app composer <command>

# Artisan
docker compose exec app php artisan <command>

# npm
docker compose exec app npm <command>
```

### よく使うコマンド

```bash
# コンテナの起動
docker compose up -d

# コンテナの停止
docker compose down

# コンテナの再起動
docker compose restart

# ログの確認
docker compose logs -f app

# データベースのリセット
docker compose exec app php artisan migrate:fresh --seed

# キャッシュのクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

## バリデーションルール

### 会員登録

- **お名前**: 必須、文字列、最大255文字
- **メールアドレス**: 必須、有効なメール形式、最大255文字、一意性
- **パスワード**: 必須、最小8文字
- **パスワード確認**: パスワードと一致すること

### ログイン

- **メールアドレス**: 必須、有効なメール形式
- **パスワード**: 必須

### エラーメッセージ

エラーメッセージは日本語で表示されます：

- **会員登録**
  - `お名前を入力してください`
  - `メールアドレスを入力してください`
  - `メールアドレスの形式が正しくありません`
  - `このメールアドレスは既に登録されています`
  - `パスワードを入力してください`
  - `パスワードは8文字以上で入力してください`
  - `パスワードと一致しません`

- **ログイン**
  - `メールアドレスを入力してください`
  - `パスワードを入力してください`
  - `ログイン情報が登録されていません`

## データベース構成

### テーブル一覧

- `users`: ユーザー情報（管理者フラグ含む）
- `attendance_records`: 勤怠記録
- `breaks`: 休憩記録
- `stamp_correction_requests`: 打刻修正申請

### 主要なリレーション

- `User` → `AttendanceRecord` (1対多)
- `AttendanceRecord` → `BreakRecord` (1対多)
- `AttendanceRecord` → `StampCorrectionRequest` (1対多)
- `User` → `StampCorrectionRequest` (1対多)

## プロジェクト構成

```
coachtech/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php       # 一般ユーザーログイン
│   │   │   │   ├── RegisterController.php    # 会員登録
│   │   │   │   └── Admin/
│   │   │   │       └── AdminLoginController.php  # 管理者ログイン
│   │   │   ├── AttendanceController.php      # 勤怠管理
│   │   │   ├── AdminController.php           # 管理者機能
│   │   │   └── StampCorrectionRequestController.php  # 修正申請
│   │   └── Requests/
│   │       └── Auth/
│   │           ├── LoginRequest.php          # ログインバリデーション
│   │           └── RegisterRequest.php       # 登録バリデーション
│   ├── Models/
│   │   ├── User.php                          # ユーザーモデル
│   │   ├── AttendanceRecord.php              # 勤怠記録モデル
│   │   ├── BreakRecord.php                   # 休憩記録モデル
│   │   └── StampCorrectionRequest.php        # 修正申請モデル
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_10_31_152839_add_is_admin_to_users_table.php
│   │   ├── 2025_10_31_152840_create_attendance_records_table.php
│   │   ├── 2025_10_31_160836_create_breaks_table.php
│   │   └── 2025_10_31_160836_create_stamp_correction_requests_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                 # メインレイアウト
│       ├── auth/
│       │   ├── login.blade.php               # ログイン画面
│       │   └── register.blade.php            # 会員登録画面
│       ├── dashboard.blade.php               # ダッシュボード（非使用）
│       ├── attendance/
│       │   ├── index.blade.php               # 打刻画面
│       │   ├── list.blade.php                # 勤怠一覧
│       │   └── detail.blade.php              # 勤怠詳細
│       ├── admin/
│       │   ├── index.blade.php               # 管理者ダッシュボード
│       │   ├── users.blade.php               # スタッフ一覧
│       │   ├── attendance_status.blade.php   # 勤怠状況
│       │   └── attendance_detail.blade.php   # 勤怠詳細（管理者）
│       └── stamp_correction_request/
│           ├── list.blade.php                # 申請一覧
│           ├── admin_list.blade.php          # 申請一覧（管理者）
│           └── approve.blade.php             # 承認画面
├── routes/
│   └── web.php                               # ルート定義
├── public/
│   └── images/
│       └── coachtech-logo.svg                # ロゴ
├── docker-compose.yml                        # Docker Compose設定
├── Dockerfile                                # Dockerイメージ定義
└── README.md
```

## 主要なルート

### 一般ユーザー

- `GET /login` - ログイン画面
- `POST /login` - ログイン処理
- `GET /register` - 会員登録画面
- `POST /register` - 会員登録処理
- `GET /attendance` - 打刻画面
- `POST /attendance/clock-in` - 出勤打刻
- `POST /attendance/clock-out` - 退勤打刻
- `POST /attendance/break-start` - 休憩開始
- `POST /attendance/break-end` - 休憩終了
- `GET /attendance/list` - 勤怠一覧
- `GET /attendance/detail/{id}` - 勤怠詳細
- `POST /attendance/detail/{id}/request-correction` - 修正申請
- `GET /stamp_correction_request/list` - 申請一覧

### 管理者

- `GET /admin/login` - 管理者ログイン画面
- `POST /admin/login` - 管理者ログイン処理
- `GET /admin/attendance/list` - 勤怠一覧（管理者）
- `GET /admin/attendance/{id}` - 勤怠詳細（管理者）
- `POST /admin/attendance/{id}/update` - 勤怠更新
- `GET /admin/staff/list` - スタッフ一覧
- `GET /admin/attendance/staff/{id}` - スタッフ別勤怠一覧
- `GET /admin/attendance/staff/{id}/csv` - CSVエクスポート
- `GET /admin/stamp_correction_request/list` - 申請一覧（管理者）
- `GET /admin/stamp_correction_request/approve/{id}` - 承認画面
- `POST /admin/stamp_correction_request/approve/{id}` - 承認処理

## デザイン

- レスポンシブデザイン対応
- Figmaデザインに準拠したUI
- モダンなカラーパレットとタイポグラフィ

## トラブルシューティング

### コンテナが起動しない

```bash
# コンテナのログを確認
docker compose logs app

# コンテナを再ビルド
docker compose down
docker compose up -d --build
```

### データベース接続エラー

`.env`ファイルのデータベース設定を確認してください：

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=coachtech
DB_USERNAME=root
DB_PASSWORD=root
```

### パーミッションエラー

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### キャッシュの問題

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

## ライセンス

Coachtechの模擬案件として作成。
