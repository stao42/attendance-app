<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 認証されていない場合はそのまま通す
        if (!Auth::check()) {
            return $next($request);
        }

        // 初回ログインユーザーの場合
        if (Auth::user()->is_first_login) {
            // プロフィール設定画面以外の場合はリダイレクト
            if (!$request->routeIs('profile.edit') && !$request->routeIs('profile.update')) {
                \Log::info('First login redirect triggered for user: ' . Auth::user()->email);
                return redirect()->route('profile.edit');
            }
        }

        return $next($request);
    }
}
