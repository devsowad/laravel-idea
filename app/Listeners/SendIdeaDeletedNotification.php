<?php

namespace App\Listeners;

use App\Events\IdeaDeletedEvent;
use App\Models\User;
use App\Notifications\IdeaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendIdeaDeletedNotification implements ShouldQueue
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
     * @param  IdeaDeletedEvent  $event
     * @return void
     */
    public function handle(IdeaDeletedEvent $event)
    {
        $users = User::where('id', $event->idea->user_id)
            ->orWhere('role', 'admin')
            ->get();

        Notification::send($users, new IdeaNotification($event->idea, 'deleted'));
    }
}
