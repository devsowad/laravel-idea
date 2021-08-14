<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'data'       => $this->data,
            'type'       => str_replace('App\\Notifications\\', '', $this->type),
            'read_at'    => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
