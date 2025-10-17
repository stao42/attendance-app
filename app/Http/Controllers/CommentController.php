<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'コメントを投稿しました。');
    }
}
