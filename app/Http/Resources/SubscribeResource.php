<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'package_id' => $this->package_id,
            'package_name' => $this->package->name ?? '',
            'subscribed_at' => $this->subscribed_date,
            'status' => $this->status,
            'primary_id' => $this->user_id,
        ];
    }
}
