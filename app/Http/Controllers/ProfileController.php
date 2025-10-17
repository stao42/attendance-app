<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $products = $user->products()->latest()->paginate(12);
        $purchases = $user->purchases()->with('product')->latest()->get();

        return view('profile.show', compact('user', 'products', 'purchases'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:5120', // 5MBまで許可、ファイル形式は自動判定
        ]);

        $data = $request->only(['name', 'postal_code', 'address', 'building']);

        if ($request->hasFile('profile_image')) {
            try {
                // 古い画像を削除
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // プロフィール画像を保存（元のファイル名を保持）
                $profileImage = $request->file('profile_image');
                $originalName = $profileImage->getClientOriginalName();
                $extension = $profileImage->getClientOriginalExtension();

                // ファイル名を安全な形式に変換（連続するアンダースコアを避ける）
                $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $safeName = preg_replace('/_+/', '_', $safeName); // 連続するアンダースコアを1つに
                $safeName = trim($safeName, '_'); // 前後のアンダースコアを削除

                // 空の場合はデフォルト名を使用
                if (empty($safeName)) {
                    $safeName = 'profile_image';
                }

                $filename = $safeName.'_'.time().'.'.$extension;

                $path = $profileImage->storeAs('profiles', $filename, 'public');
                $data['profile_image'] = $path;

                // デバッグ情報
                Log::info('Profile image uploaded', [
                    'user_id' => $user->id,
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'file_exists' => Storage::disk('public')->exists($path),
                ]);

            } catch (\Exception $e) {
                Log::error('Profile image upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                return back()->withErrors(['profile_image' => '画像のアップロードに失敗しました。']);
            }
        }

        $user->update($data);

        // デバッグ情報
        Log::info('Profile updated', [
            'user_id' => $user->id,
            'profile_image' => $user->fresh()->profile_image,
            'data' => $data,
        ]);

        // 初回ログインの場合、フラグを更新
        if ($user->is_first_login) {
            $user->update(['is_first_login' => false]);
        }

        return redirect()->route('profile.show')->with('success', 'プロフィールを更新しました。');
    }
}
