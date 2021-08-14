<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Models\User;
use App\Notifications\CommentAddedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentAddedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        $event->comment->load('idea:user_id,id,slug');
        $user = User::find($event->comment->idea->user_id);

        $user->notify(new CommentAddedNotification($event->comment));
    }
}
