<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PermissionStoreRequest;
use App\Http\Requests\Auth\PermissionUpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Template;
use App\Models\User;
use App\Repositories\Eloquent\PermissionsRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    private $repo;

    /**
     * Create a new controller instance.
     *
     * @param PermissionsRepository $repo
     */
    public function __construct(PermissionsRepository $repo)
    {
        $this->repo = $repo;
//        $this->middleware('permission:list-templates|edit-templates|delete-templates|create-templates', ['only' => ['index']]);
//        $this->middleware('permission:create-templates', ['only' => ['store']]);
//        $this->middleware('permission:edit-templates', ['only' => ['update']]);
//        $this->middleware('permission:delete-templates', ['only' => ['destroy']]);
    }


    /**
     * Show the application dashboard.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $user = \Auth::user();
        $permissions = [];
        if(in_array('Root', $user->getRoleNames()->toArray()))
        {
            $permissions = $this->repo->all()->groupBy('group');
        }
        elseif (in_array('Employee', $user->getRoleNames()->toArray()) || in_array('Admin', $user->getRoleNames()->toArray()))
        {
            $permissions = $user->getAllPermissions()->groupBy('group');
        }
//        $permissions['defaultTemplates'] = Template::pluck('name')->toArray();
        return  $permissions;
//        return response()->json($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionStoreRequest $request
     * @return JsonResponse
     */
    public function store(PermissionStoreRequest $request)
    {
        try {
            return $this->repo->create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'guard_name' => "api",
                'group' => $request->group,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    /**
     * update a permission.
     *
     * @param PermissionUpdateRequest $request
     * @return JsonResponse
     */
    public function update(PermissionUpdateRequest $request)
    {
        try {
            return $this->repo->update($request->id, [
                'name' => $request->name,
                'display_name' => $request->display_name,
                'group' => $request->group,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }


    /**
     * Delete more than one permission.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repo->find($id)->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
}
