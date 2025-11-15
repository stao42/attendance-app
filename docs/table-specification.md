# テーブル仕様書

| No. | テーブル名 | カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|-----|-----------|---------|-----|------------|-----------|----------|-------------|
| 1 | users | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | name | varchar(255) | | | ○ | |
| | | email | varchar(255) | | ○ | ○ | |
| | | email_verified_at | timestamp | | | | |
| | | password | varchar(255) | | | ○ | |
| | | is_admin | boolean | | | | |
| | | remember_token | varchar(100) | | | | |
| | | created_at | timestamp | | | | |
| | | updated_at | timestamp | | | | |
| 2 | attendance_records | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | user_id | unsigned bigint | | | ○ | users(id) |
| | | date | date | | | ○ | |
| | | clock_in | time | | | | |
| | | clock_out | time | | | | |
| | | break_time | time | | | | |
| | | work_time | time | | | | |
| | | notes | text | | | | |
| | | created_at | timestamp | | | | |
| | | updated_at | timestamp | | | | |
| | | (user_id, date) | | | ○ | | |
| 3 | breaks | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | attendance_record_id | unsigned bigint | | | ○ | attendance_records(id) |
| | | break_start | time | | | | |
| | | break_end | time | | | | |
| | | break_duration | integer | | | | |
| | | created_at | timestamp | | | | |
| | | updated_at | timestamp | | | | |
| 4 | stamp_correction_requests | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | attendance_record_id | unsigned bigint | | | ○ | attendance_records(id) |
| | | user_id | unsigned bigint | | | ○ | users(id) |
| | | requested_clock_in | time | | | | |
| | | requested_clock_out | time | | | | |
| | | requested_notes | text | | | | |
| | | status | enum('pending', 'approved', 'rejected') | | | | |
| | | approved_by | unsigned bigint | | | | users(id) |
| | | approved_at | timestamp | | | | |
| | | created_at | timestamp | | | | |
| | | updated_at | timestamp | | | | |
| 5 | password_reset_tokens | | | | | | |
| | | email | varchar(255) | ○ | | ○ | |
| | | token | varchar(255) | | | ○ | |
| | | created_at | timestamp | | | | |
| 6 | sessions | | | | | | |
| | | id | varchar(255) | ○ | | ○ | |
| | | user_id | unsigned bigint | | | | users(id) |
| | | ip_address | varchar(45) | | | | |
| | | user_agent | text | | | | |
| | | payload | longtext | | | ○ | |
| | | last_activity | integer | | | ○ | |
| 7 | cache | | | | | | |
| | | key | varchar(255) | ○ | | ○ | |
| | | value | mediumtext | | | ○ | |
| | | expiration | integer | | | ○ | |
| 8 | cache_locks | | | | | | |
| | | key | varchar(255) | ○ | | ○ | |
| | | owner | varchar(255) | | | ○ | |
| | | expiration | integer | | | ○ | |
| 9 | jobs | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | queue | varchar(255) | | | ○ | |
| | | payload | longtext | | | ○ | |
| | | attempts | unsigned tinyint | | | ○ | |
| | | reserved_at | unsigned integer | | | | |
| | | available_at | unsigned integer | | | ○ | |
| | | created_at | unsigned integer | | | ○ | |
| 10 | job_batches | | | | | | |
| | | id | varchar(255) | ○ | | ○ | |
| | | name | varchar(255) | | | ○ | |
| | | total_jobs | integer | | | ○ | |
| | | pending_jobs | integer | | | ○ | |
| | | failed_jobs | integer | | | ○ | |
| | | failed_job_ids | longtext | | | ○ | |
| | | options | mediumtext | | | | |
| | | cancelled_at | integer | | | | |
| | | created_at | integer | | | ○ | |
| | | finished_at | integer | | | | |
| 11 | failed_jobs | | | | | | |
| | | id | unsigned bigint | ○ | | ○ | |
| | | uuid | varchar(255) | | ○ | ○ | |
| | | connection | text | | | ○ | |
| | | queue | text | | | ○ | |
| | | payload | longtext | | | ○ | |
| | | exception | longtext | | | ○ | |
| | | failed_at | timestamp | | | ○ | |

## 補足説明

### 1. users（ユーザーテーブル）
- 一般ユーザーと管理者の両方の情報を格納
- `is_admin`が`true`の場合、管理者権限を持つ
- `email`は一意制約あり

### 2. attendance_records（勤怠記録テーブル）
- ユーザーの日別の勤怠記録を格納
- `user_id`と`date`の組み合わせで一意制約あり（1ユーザー1日1レコード）
- `break_time`はデフォルト値`00:00:00`
- `clock_in`、`clock_out`は任意（NULL可）

### 3. breaks（休憩記録テーブル）
- 勤怠記録に紐づく休憩記録を格納
- `break_duration`は秒数で保存
- 1つの勤怠記録に対して複数の休憩記録を持つことができる

### 4. stamp_correction_requests（打刻修正申請テーブル）
- 勤怠記録の修正申請を格納
- `status`は`pending`（承認待ち）、`approved`（承認済み）、`rejected`（却下）のいずれか
- `approved_by`は承認した管理者のユーザーID（NULL可）
- `approved_at`は承認日時（NULL可）

### 5-11. Laravel標準テーブル
- `password_reset_tokens`: パスワードリセット用トークン
- `sessions`: セッション情報
- `cache`, `cache_locks`: キャッシュ管理
- `jobs`, `job_batches`, `failed_jobs`: ジョブキュー管理

## 外部キー制約

- `attendance_records.user_id` → `users.id` (CASCADE DELETE)
- `attendance_records`の`(user_id, date)`にUNIQUE制約
- `breaks.attendance_record_id` → `attendance_records.id` (CASCADE DELETE)
- `stamp_correction_requests.attendance_record_id` → `attendance_records.id` (CASCADE DELETE)
- `stamp_correction_requests.user_id` → `users.id` (CASCADE DELETE)
- `stamp_correction_requests.approved_by` → `users.id` (SET NULL)
- `sessions.user_id` → `users.id` (NULL可)

