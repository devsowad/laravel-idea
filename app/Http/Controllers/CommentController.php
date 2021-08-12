<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(CommentCreateRequest $request)
    {
        $comment = Comment::create([
            'idea_id' => $request->idea_id,
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ]);

        $comment->load('user:id,name');
        return $comment;
    }

    public function show(Comment $comment)
    {
        //
    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        return $comment->delete();
    }

    public function markAsSpam()
    {
        $comment = Comment::findOrFail(request()->id);
        $comment->increment('spam_reports');
        return ['spam_reports' => $comment->spam_reports];
    }

    public function notSpam()
    {
        $this->authorize('notSpam', Comment::class);

        $comment = Comment::findOrFail(request()->id);
        $comment->update(['spam_reports' => 0]);
        return ['spam_reports' => $comment->spam_reports];
    }
}
