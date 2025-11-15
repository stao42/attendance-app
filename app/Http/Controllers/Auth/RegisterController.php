<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * 新規登録画面を表示
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 新規登録処理（Fortify使用）
     */
    public function register(RegisterRequest $request, CreateNewUser $createNewUser)
    {
        // RegisterRequestでバリデーション済みのデータを取得
        $validated = $request->validated();
        
        // password_confirmationを追加（Fortifyのアクションが期待する形式）
        $input = array_merge($validated, [
            'password_confirmation' => $request->input('password_confirmation'),
        ]);

        // FortifyのCreateNewUserアクションを使用
        $user = $createNewUser->create($input);

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
