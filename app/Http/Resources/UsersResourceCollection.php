<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersResourceCollection extends ResourceCollection
{
    /**
     * @var array
     */
    protected $allowed = [];
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->processCollection($request);
    }
    public function hide(array $fields)
    {
        $this->allowed = $fields;
        return $this;
    }
    /**
     * Send fields to hide to UsersResource while processing the collection.
     *Processing collection of hidden fields through usersresource
     * 
     * @param $request
     * @return array
     */
    protected function processCollection($request)
    {
        return $this->collection->map(function (UsersResource $resource) use ($request) {
            return $resource->hide($this->withoutFields)->toArray($request);
        })->all();
    }
}