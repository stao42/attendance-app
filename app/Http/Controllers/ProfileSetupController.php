<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileSetupController extends Controller
{
    public function show()
    {
        return view('profile.setup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'postal_code.max' => '郵便番号は20文字以内で入力してください',
            'address.max' => '住所は255文字以内で入力してください',
            'building.max' => '建物名は255文字以内で入力してください',
            'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください',
            'profile_image.mimes' => 'プロフィール画像はjpeg、png、jpg、gif形式のファイルを選択してください',
            'profile_image.max' => 'プロフィール画像は2MB以下のファイルを選択してください',
        ]);

        $user = Auth::user();
        $data = $request->only(['postal_code', 'address', 'building']);

        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            // 古い画像を削除
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update($data);
        
        // 初回ログインフラグを更新
        $user->update(['is_first_login' => false]);

        return redirect()->route('products.index')->with('success', 'プロフィール設定が完了しました。');
    }
}
