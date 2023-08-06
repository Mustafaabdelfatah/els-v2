<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivitylogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'description' => $this->description,
            'subject_type' => $this->subject_type,
            'subject_id' => $this->subject_id,
            'causer_type' => $this->causer_type,
            'causer_id' => $this->causer_id,
            'user' => $this->user,
//            'properties' => serialize($this->properties['attributes']),
            'properties' => $this->properties,
            'created_at' => $this->created_at->format('Y-m-d'),
//            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
