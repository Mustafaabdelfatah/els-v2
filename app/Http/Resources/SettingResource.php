<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $request;
//        return [
//            'id' => $this->id,
//            'key' => $this->key,
//            'value' => $this->value,
//            'group' => $this->group,
//            'type' => $this->type,
//        ];
    }
}
