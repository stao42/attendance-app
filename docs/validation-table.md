# バリデーション一覧表

| バリデーションファイル名 | フォーム | ルール |
|------------------------|---------|--------|
| `App\Http\Requests\Auth\RegisterRequest` | 会員登録フォーム（一般ユーザー） | `name`: required, string, max:255<br>`email`: required, string, email, max:255, unique:users<br>`password`: required, string, min:8, confirmed |
| `App\Http\Requests\Auth\LoginRequest` | ログインフォーム（一般ユーザー） | `email`: required, email<br>`password`: required |
| `App\Http\Requests\Auth\AdminLoginRequest` | ログインフォーム（管理者） | `email`: required, email<br>`password`: required |
| `AttendanceController::requestCorrection`<br>（コントローラー内で直接バリデーション） | 修正申請フォーム（一般ユーザー） | `clock_in`: required, date_format:H:i<br>`clock_out`: nullable, date_format:H:i<br>`notes`: required, string<br>`break_starts`: nullable, array<br>`break_starts.*`: nullable, date_format:H:i<br>`break_ends`: nullable, array<br>`break_ends.*`: nullable, date_format:H:i<br><br>**追加チェック**: clock_outが存在する場合、clock_out > clock_in であること |
| `AdminController::updateAttendance`<br>（コントローラー内で直接バリデーション） | 勤怠更新フォーム（管理者） | `clock_in`: required, date_format:H:i<br>`clock_out`: nullable, date_format:H:i<br>`notes`: required, string<br>`break_starts`: nullable, array<br>`break_starts.*`: nullable, date_format:H:i<br>`break_ends`: nullable, array<br>`break_ends.*`: nullable, date_format:H:i<br><br>**追加チェック**: clock_outが存在する場合、clock_out > clock_in であること |

## バリデーションルールの詳細

### RegisterRequest（会員登録）

- **name**: 必須、文字列、最大255文字
- **email**: 必須、メール形式、最大255文字、usersテーブルで一意
- **password**: 必須、文字列、最小8文字、確認用パスワードと一致

### LoginRequest / AdminLoginRequest（ログイン）

- **email**: 必須、メール形式
- **password**: 必須

### 修正申請・勤怠更新フォーム

- **clock_in**: 必須、時刻形式（H:i、例：09:00）
- **clock_out**: 任意、時刻形式（H:i、例：18:00）
- **notes**: 必須、文字列
- **break_starts**: 任意、配列（休憩開始時刻の配列）
- **break_starts.***: 配列の各要素は任意、時刻形式（H:i）
- **break_ends**: 任意、配列（休憩終了時刻の配列）
- **break_ends.***: 配列の各要素は任意、時刻形式（H:i）

**カスタムバリデーション**:
- `clock_out`が存在する場合、`clock_out`は`clock_in`より後である必要があります

## カスタムエラーメッセージ

### RegisterRequest

- `name.required`: お名前を入力してください
- `email.required`: メールアドレスを入力してください
- `email.email`: メールアドレスの形式が正しくありません
- `email.unique`: このメールアドレスは既に登録されています
- `password.required`: パスワードを入力してください
- `password.min`: パスワードは8文字以上で入力してください
- `password.confirmed`: パスワードと一致しません

### LoginRequest / AdminLoginRequest

- `email.required`: メールアドレスを入力してください
- `email.email`: メールアドレスを入力してください
- `password.required`: パスワードを入力してください

## 注意事項

- 修正申請フォームと勤怠更新フォームは、コントローラー内で直接`$request->validate()`を使用してバリデーションを行っています
- これらのフォームでは、承認待ちの修正申請がある場合は更新できないというビジネスロジックチェックも実装されています
