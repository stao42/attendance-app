# ルーティング一覧表

| 画面名称 | パス | メソッド | ルート先コントローラー | アクション | 認証必須 | 説明 |
|---------|------|---------|---------------------|-----------|---------|------|
| 会員登録画面（一般ユーザー） | `/register` | GET | `App\Http\Controllers\Auth\RegisterController` | `showRegistrationForm` | 不要 | 新規ユーザー登録画面 |
| 会員登録処理（一般ユーザー） | `/register` | POST | `App\Http\Controllers\Auth\RegisterController` | `register` | 不要 | 新規ユーザー登録処理 |
| ログイン画面（一般ユーザー） | `/login` | GET | `App\Http\Controllers\Auth\LoginController` | `showLoginForm` | 不要 | 一般ユーザーログイン画面 |
| ログイン処理（一般ユーザー） | `/login` | POST | `App\Http\Controllers\Auth\LoginController` | `login` | 不要 | 一般ユーザーログイン処理 |
| ログアウト処理（一般ユーザー） | `/logout` | POST | `App\Http\Controllers\Auth\LoginController` | `logout` | 必須 | 一般ユーザーログアウト処理 |
| 出勤登録画面（一般ユーザー） | `/attendance` | GET | `App\Http\Controllers\AttendanceController` | `index` | 必須 | 出勤/退勤/休憩の打刻画面（PG03） |
| 出勤打刻処理（一般ユーザー） | `/attendance/clock-in` | POST | `App\Http\Controllers\AttendanceController` | `clockIn` | 必須 | 出勤打刻処理 |
| 退勤打刻処理（一般ユーザー） | `/attendance/clock-out` | POST | `App\Http\Controllers\AttendanceController` | `clockOut` | 必須 | 退勤打刻処理 |
| 休憩開始処理（一般ユーザー） | `/attendance/break-start` | POST | `App\Http\Controllers\AttendanceController` | `breakStart` | 必須 | 休憩開始打刻処理 |
| 休憩終了処理（一般ユーザー） | `/attendance/break-end` | POST | `App\Http\Controllers\AttendanceController` | `breakEnd` | 必須 | 休憩終了打刻処理 |
| 勤怠一覧画面（一般ユーザー） | `/attendance/list` | GET | `App\Http\Controllers\AttendanceController` | `list` | 必須 | 月別の勤怠記録一覧（PG04） |
| 勤怠詳細画面（一般ユーザー） | `/attendance/detail/{id}` | GET | `App\Http\Controllers\AttendanceController` | `detail` | 必須 | 勤怠記録の詳細表示・修正申請（PG05） |
| 修正申請処理（一般ユーザー） | `/attendance/detail/{id}/request-correction` | POST | `App\Http\Controllers\AttendanceController` | `requestCorrection` | 必須 | 打刻修正申請の送信処理 |
| 申請一覧画面（一般ユーザー） | `/stamp_correction_request/list` | GET | `App\Http\Controllers\StampCorrectionRequestController` | `list` | 必須 | 修正申請の一覧（承認待ち/承認済み）（PG06） |
| ログイン画面（管理者） | `/admin/login` | GET | `App\Http\Controllers\Auth\Admin\AdminLoginController` | `showLoginForm` | 不要 | 管理者ログイン画面 |
| ログイン処理（管理者） | `/admin/login` | POST | `App\Http\Controllers\Auth\Admin\AdminLoginController` | `login` | 不要 | 管理者ログイン処理 |
| ログアウト処理（管理者） | `/admin/logout` | POST | `App\Http\Controllers\Auth\Admin\AdminLoginController` | `logout` | 必須 | 管理者ログアウト処理 |
| 勤怠一覧画面（管理者） | `/admin/attendance/list` | GET | `App\Http\Controllers\AdminController` | `attendanceList` | 必須 | 全スタッフの勤怠一覧（PG08） |
| 勤怠詳細画面（管理者） | `/admin/attendance/{id}` | GET | `App\Http\Controllers\AdminController` | `attendanceDetail` | 必須 | 勤怠記録の詳細表示・更新（PG09） |
| 勤怠更新処理（管理者） | `/admin/attendance/{id}/update` | POST | `App\Http\Controllers\AdminController` | `updateAttendance` | 必須 | 勤怠記録の更新処理 |
| スタッフ一覧画面（管理者） | `/admin/staff/list` | GET | `App\Http\Controllers\AdminController` | `staffList` | 必須 | スタッフ一覧（PG10） |
| スタッフ別勤怠一覧画面（管理者） | `/admin/attendance/staff/{id}` | GET | `App\Http\Controllers\AdminController` | `staffAttendanceList` | 必須 | スタッフ別の勤怠一覧（PG11） |
| スタッフ別勤怠CSVエクスポート（管理者） | `/admin/attendance/staff/{id}/csv` | GET | `App\Http\Controllers\AdminController` | `staffAttendanceCsv` | 必須 | スタッフ別勤怠のCSVエクスポート |
| 申請一覧画面（管理者） | `/stamp_correction_request/list` | GET | `App\Http\Controllers\StampCorrectionRequestController` | `list` | 必須 | 修正申請の一覧（管理者用）（PG12） |
| 修正申請承認画面（管理者） | `/stamp_correction_request/approve/{attendance_correct_request_id}` | GET | `App\Http\Controllers\StampCorrectionRequestController` | `approveDetail` | 必須 | 修正申請の承認画面（PG13） |
| 修正申請承認処理（管理者） | `/stamp_correction_request/approve/{attendance_correct_request_id}` | POST | `App\Http\Controllers\StampCorrectionRequestController` | `approve` | 必須 | 修正申請の承認処理 |
| 修正申請却下処理（管理者） | `/stamp_correction_request/reject/{attendance_correct_request_id}` | POST | `App\Http\Controllers\StampCorrectionRequestController` | `reject` | 必須 | 修正申請の却下処理 |

## 認証ミドルウェア

- **認証必須**: `auth` ミドルウェアが適用されているルート
- **認証不要**: ミドルウェアが適用されていないルート（一般ユーザー・管理者のログイン/登録画面）

## 注意事項

- 一般ユーザーと管理者で同じパス `/stamp_correction_request/list` を使用していますが、コントローラー内で権限チェックを行っています
- 管理者の修正申請承認画面は、コントローラー内で管理者権限をチェックしています
