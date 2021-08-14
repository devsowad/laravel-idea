<?php

namespace App\Notifications;

use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdeaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Idea $idea, private string $action)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = $notifiable->id == $this->idea->id
        ? "Your idea has been $this->action"
        : "$notifiable->name $this->action an idea";

        return [
            'title' => $title,
            'idea'  => [
                'id'    => $this->idea->id,
                'slug'  => $this->idea->slug,
                'title' => $this->idea->title,
            ],
            'user'  => [
                'name'       => $notifiable->name,
                'id'         => $notifiable->id,
                'avatar_url' => $notifiable->avatar_url,
            ],
        ];
    }
}
