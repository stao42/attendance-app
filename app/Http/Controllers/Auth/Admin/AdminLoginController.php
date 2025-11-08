<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * 管理者ログイン画面を表示
     */
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    /**
     * 管理者ログイン処理
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 管理者権限チェック
            if (!Auth::user()->is_admin) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => '管理者権限がありません',
                ]);
            }

            // 管理者画面へリダイレクト
            return redirect()->intended('/admin/attendance/list');
        }

        throw ValidationException::withMessages([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /**
     * 管理者ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
