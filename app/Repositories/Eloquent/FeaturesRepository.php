<?php


namespace App\Repositories\Eloquent;

use App\Models\Feature;
use App\Repositories\Contracts\IFeature;

class FeaturesRepository extends BaseRepository implements IFeature
{
    public function model()
    {
        return Feature::class;
    }
}
