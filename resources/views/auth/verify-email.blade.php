@extends('layouts.app')

@section('title', 'メール認証のお願い')

@section('content')
<div class="min-h-screen bg-[#F0EFF2] flex items-center justify-center py-16 px-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-8 space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-2xl font-bold tracking-wide">メール認証が必要です</h1>
            <p class="text-gray-600 leading-relaxed">
                会員登録時にご入力いただいたメールアドレス宛に認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>
        </div>

        @if(session('status') === 'verification-link-sent')
            <div class="p-4 rounded-lg bg-green-100 text-green-800 text-sm font-semibold">
                認証メールを再送しました。メールボックスをご確認ください。
            </div>
        @endif

        <div class="space-y-4">
            @if($mailPreviewUrl = config('mail.preview_url'))
                <a href="{{ $mailPreviewUrl }}" target="_blank" rel="noopener"
                   class="w-full inline-flex items-center justify-center border border-black rounded-xl py-3 font-bold tracking-widest hover:bg-black hover:text-white transition">
                    認証はこちらから
                </a>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit"
                    class="w-full bg-black text-white rounded-xl py-3 font-bold tracking-widest hover:opacity-80 transition">
                    認証メールを再送
                </button>
            </form>

            <p class="text-sm text-gray-500 text-center">
                メールが届かない場合は迷惑メールフォルダもご確認ください。<br>
                それでも届かない場合は管理者までご連絡ください。
            </p>
        </div>
    </div>
</div>
@endsection
