<?php


namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUser;

class AgentsRepository extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }

    public function all()
    {
        return $this->getModelClass()->where('primary_id', null)->get();
    }
}
