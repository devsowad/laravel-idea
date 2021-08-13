<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Queries\v1\CommentQuery;
use App\Http\Requests\v1\CommentCreateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(CommentQuery $query)
    {
        return $query->simplePaginate(request()->limit);
    }

    public function store(CommentCreateRequest $request)
    {
        $comment = Comment::create([
            'idea_id'      => $request->idea_id,
            'body'         => $request->body,
            'user_id'      => auth()->id(),
            'spam_reports' => 0,
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

    public function markAsSpam(Comment $comment)
    {
        $comment->increment('spam_reports');
        return ['spam_reports' => $comment->spam_reports];
    }

    public function notSpam(Comment $comment)
    {
        $this->authorize('notSpam', Comment::class);

        $comment->update(['spam_reports' => 0]);
        return ['spam_reports' => $comment->spam_reports];
    }
}
