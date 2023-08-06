<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageFeaturesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'countable' => $this->countable,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'max_count' => $this->pivot->max_count
        ];
    }
}
