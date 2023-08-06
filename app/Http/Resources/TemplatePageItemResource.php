<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed childList
 * @property mixed type
 * @property mixed label
 * @property mixed notes
 * @property mixed comment
 * @property mixed width
 * @property mixed height
 * @property mixed enabled
 * @property mixed required
 * @property mixed website_view
 * @property mixed excel_name
 */
class TemplatePageItemResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'excel_name' => $this->excel_name,
            'notes' => $this->notes,
            'comment' => $this->comment,
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
            'input_type' => $this->input_type,
            'enabled' => $this->enabled,
            'required' => $this->required,
            'website_view' => $this->website_view,
            'childList' => json_decode($this->childList),
        ];
    }
}
