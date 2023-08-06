<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReturnRequestCollection extends JsonResource
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
            "id" => $this->id,
            "Priority" => $this->Priority,
            "end_date" => $this->end_date,
            "is_secret" => $this->is_secret,
            "approve_secret" => $this->approve_secret,
            "file" => $this->file,
            "informationText" => $this->informationText,
            "value" => $this->value,
            "status" => $this->status,
            "form" => $this->form,
            "user" => $this->user,
            "expected_amount" => $this->expected_amount,
            "created_at" => \Carbon\Carbon::parse($this->created_at)->diffForHumans(),
            "updated_at" => \Carbon\Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
