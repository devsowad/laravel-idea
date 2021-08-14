<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\NotificationResource;

class NotificationController
{
    public function all()
    {
        return NotificationResource::collection(
            auth()
                ->user()
                ->notifications()
                ->select(['id', 'data', 'type', 'read_at', 'created_at'])
                ->paginate(10)
        );
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications->markAsRead();
        return now();
    }
}
