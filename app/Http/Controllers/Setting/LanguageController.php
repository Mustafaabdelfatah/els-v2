<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageStoreRequest;
use App\Http\Requests\LanguageUpdateRequest;
use App\Http\Resources\LanguageResource;
use App\Models\User;
use App\Repositories\Eloquent\LanguageRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    private $repo;

    public function __construct(LanguageRepository $repo)
    {
        $this->repo = $repo;
        $this->middleware('permission:list-languages|edit-languages|delete-languages|create-languages', ['only' => ['index']]);
        $this->middleware('permission:create-languages', ['only' => ['store']]);
        $this->middleware('permission:edit-languages', ['only' => ['update']]);
        $this->middleware('permission:delete-languages', ['only' => ['destroy']]);

        // $this->middleware('permission:user-get-languages', ['only' => ['get']]);
    }


    /**
     * Show the application dashboard.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return LanguageResource::collection($this->repo->all());
    }

    /**
     * get users selected languages
     *
     * @return AnonymousResourceCollection
     */
    public function get()
    {
        return LanguageResource::collection(User::find(primaryID())->languages);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param LanguageStoreRequest $request
     * @return LanguageResource|JsonResponse
     */
    public function store(LanguageStoreRequest $request)
    {
        try {
            $language = $this->repo->create([
                'name' => $request->name,
                'code' => $request->code,
                'direction' => $request->direction,
                'flag' => $request->flag,
                'user_id' => auth::id(),
            ]);

            return new LanguageResource($language);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    /**
     * update a permission.
     *
     * @param LanguageUpdateRequest $request
     * @return LanguageResource|JsonResponse
     */
    public function update(LanguageUpdateRequest $request)
    {
        try {
            $language = $this->repo->update($request->id, [
                'name' => $request->name,
                'code' => $request->code,
                'direction' => $request->direction,
                'flag' => $request->flag,
            ]);
            return new LanguageResource($language);
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
