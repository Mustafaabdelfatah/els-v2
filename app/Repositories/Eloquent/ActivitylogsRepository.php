<?php
namespace App\Repositories\Eloquent;

use Spatie\Activitylog\Models\Activity;
use Exception;
use Illuminate\Support\Facades\DB;

class ActivitylogsRepository extends BaseRepository
{
    public function model()
    {
        return Activity::class;
    }
}
