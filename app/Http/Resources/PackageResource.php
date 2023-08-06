<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'name' => $this->name,
            'desc' => $this->desc,
            'type' => $this->type,
            'count' => $this->count,
            'price' => $this->price,
            'is_offer' => $this->is_offer == 0 ? false : true,
            'is_used' => $this->is_used == 0 ? false : true,
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d') : '',
            'expire_date' => $this->expire_date ? Carbon::parse($this->expire_date)->format('Y-m-d') : '',
            'features' => PackageFeaturesResource::collection($this->features),
            'features_excluded' => FeatureResource::collection($this->features_excluded()),
        ];
    }
}
