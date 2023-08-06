<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\updateProfileRequest;
use Exception;
use App\Models\User;
use File;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Eloquent\UsersRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class MeController extends Controller
{
    private $repo;

    public function __construct(UsersRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getMe()
    {
        if (auth()->check())
            $user = User::with('organization_admin','organization_admin.organization')->where('id','=',\Auth::id())->first();
            return response()->json(new UserResource($user));

        return response()->json(null, 401);
    }

    public function updateProfileInfo(updateProfileRequest $request)
    {
        try {
            $postData = [
                'name' => $request->name,
                // 'username' => $request->username,
            ];
            if (request()->hasFile('image') && $request->image != '') {
                $user = User::find($request->id);
                if ($user->image) {
                    $imagePath = storage_path('profile/' . $user->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $postData['image'] = uploadImage('tmp', $request->image, 'profile');
            }
            if (request()->has('phone') && $request->phone != '') {
                $postData['phone'] = $request->phone;
            }
            if (request()->has('newpassword') && $request->newpassword != '') {
                $postData['password'] = bcrypt($request->newpassword);
            }
            return $this->repo->updateProfile($request->id, $request->password, $postData);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
}
