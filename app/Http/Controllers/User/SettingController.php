<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Department;
use App\Models\Role;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\UsersRepository;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;

class SettingController extends Controller
{
    protected $users;
    public function __construct(UsersRepository $user)
    {
        $this->users = $user;
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'tagline'            => ['required'],
            'name'               => ['required'],
            'available_to_hire'  => ['required'],
            'about'              => ['required','string','min:20'],
            'formatted_address'  => ['required'],
            'location.latitude'  => ['required','numeric','min:-90','max:90'],
            'location.longitude' => ['required','numeric','min:-180','max:180']
        ]);

        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $user = $this->users->update($user->id, [
            'name' => $request->name,
            'about' => $request->about,
            'tagline' => $request->tagline,
            'formatted_address' => $request->tagline,
            'location' => $location,
            'available_to_hire' => $request->available_to_hire,
        ]);
        return new UserResource($user);
    }

    public function getUserData()
    {
        $user = auth()->user();
        return response()->json(['user'=>$user], 200);
    }

    public function updatePassword(Request $request)
    {
        $data = [];
        $this->validate($request, [
//            'avatar'=>['image','mimes:jpeg,jpg,png'],
            'email'=> 'required|email|unique:users,email,'.auth()->user()->id
//            'password'=>['required','min:6','confirmed',new CheckSamePassword],
//            'current_password' => ['required',new MatchOldPassword]
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['department_id'] = $request->department_id;
        $data['password'] = bcrypt($request->password);
        $data['user_reset_password'] = auth()->user()->id;

        if (!empty($request['avatar']))
            $data['avatar'] = uploadFile($request['avatar'], 'media/profile');

        $user = $this->users->update(auth()->user()->id,$data);

        return response()->json($user->load('roles','notifications','permissions'), 200);
    }
}
