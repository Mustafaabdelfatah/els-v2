<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LdapRecord\Models\Attributes\Guid;

class UserController extends Controller
{

    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->withCriteria(
            new EagerLoad(['designs'])
        )->all();
        return UserResource::collection($users);
    }

}
