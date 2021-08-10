<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
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
        $comment = Comment::findOrFail(request()->id);
        $comment->update(['spam_reports' => 0]);
        return ['spam_reports' => $comment->spam_reports];
    }
}
