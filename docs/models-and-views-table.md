# モデル・ビュー一覧表

## Model

| モデルファイル名 | 説明 |
|----------------|------|
| `User.php` | ユーザー情報（一般ユーザー・管理者）を管理するモデル。`is_admin`フラグで管理者と一般ユーザーを区別。勤怠記録との1対多のリレーションを持つ。 |
| `AttendanceRecord.php` | 勤怠記録を管理するモデル。出勤・退勤時刻、休憩時間、勤務時間、備考などを保持。ユーザー、休憩記録、修正申請とのリレーションを持つ。 |
| `BreakRecord.php` | 休憩記録を管理するモデル。休憩開始・終了時刻、休憩時間を保持。勤怠記録との多対1のリレーションを持つ。 |
| `StampCorrectionRequest.php` | 打刻修正申請を管理するモデル。申請された出勤・退勤時刻、申請理由、承認状態、承認者情報などを保持。勤怠記録とユーザーとのリレーションを持つ。 |

## View

| 画面名称 | bladeファイル名 |
|---------|----------------|
| 会員登録画面（一般ユーザー） | `auth.register` (`resources/views/auth/register.blade.php`) |
| ログイン画面（一般ユーザー） | `auth.login` (`resources/views/auth/login.blade.php`) |
| 出勤登録画面（一般ユーザー） | `attendance.index` (`resources/views/attendance/index.blade.php`) |
| 勤怠一覧画面（一般ユーザー） | `attendance.list` (`resources/views/attendance/list.blade.php`) |
| 勤怠詳細画面（一般ユーザー） | `attendance.detail` (`resources/views/attendance/detail.blade.php`) |
| 申請一覧画面（一般ユーザー） | `stamp_correction_request.list` (`resources/views/stamp_correction_request/list.blade.php`) |
| ログイン画面（管理者） | `auth.admin.login` (`resources/views/auth/admin/login.blade.php`) |
| 勤怠一覧画面（管理者） | `admin.attendance_list` (`resources/views/admin/attendance_list.blade.php`) |
| 勤怠詳細画面（管理者） | `admin.attendance_detail` (`resources/views/admin/attendance_detail.blade.php`) |
| スタッフ一覧画面（管理者） | `admin.staff_list` (`resources/views/admin/staff_list.blade.php`) |
| スタッフ別勤怠一覧画面（管理者） | `admin.staff_attendance_list` (`resources/views/admin/staff_attendance_list.blade.php`) |
| 申請一覧画面（管理者） | `stamp_correction_request.admin_list` (`resources/views/stamp_correction_request/admin_list.blade.php`) |
| 修正申請承認画面（管理者） | `stamp_correction_request.approve` (`resources/views/stamp_correction_request/approve.blade.php`) |

## モデルのリレーション

### User（ユーザー）
- `hasMany(AttendanceRecord::class)` - ユーザーは複数の勤怠記録を持つ

### AttendanceRecord（勤怠記録）
- `belongsTo(User::class)` - 勤怠記録は1つのユーザーに属する
- `hasMany(BreakRecord::class)` - 勤怠記録は複数の休憩記録を持つ
- `hasMany(StampCorrectionRequest::class)` - 勤怠記録は複数の修正申請を持つ

### BreakRecord（休憩記録）
- `belongsTo(AttendanceRecord::class)` - 休憩記録は1つの勤怠記録に属する

### StampCorrectionRequest（打刻修正申請）
- `belongsTo(AttendanceRecord::class)` - 修正申請は1つの勤怠記録に属する
- `belongsTo(User::class)` - 修正申請は申請したユーザーに属する
- `belongsTo(User::class, 'approved_by')` - 修正申請は承認した管理者に属する（approver）
