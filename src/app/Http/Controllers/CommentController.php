<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // コメントの追加
    public function store(CommentRequest $request, Item $item)
    {

        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('status', 'コメントが追加されました。');
    }

    // コメントの削除
    public function destroy(Comment $comment)
    {
        // コメントの所有者のみが削除できるようにする
        if (Auth::id() === $comment->user_id) {
            $comment->delete();
            return redirect()->back()->with('status', 'コメントが削除されました。');
        }

        return redirect()->back()->with('error', 'コメントを削除する権限がありません。');
    }
}
