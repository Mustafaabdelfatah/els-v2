<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Repositories\Eloquent\DepartmentsRepository;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private $repo;

    public function __construct(DepartmentsRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $orders = [];
        $query = [];
        if ($request->has('order') && count($request->input('order')))
            foreach ($request->input('order') as $order) {
                $column = isset($request->input("columns")[$order['column']]['data']) ? $request->input("columns")[$order['column']]['data'] : null;
                if ($column)
                    $orders[$column] = $order['dir'];
                else {

                }
            }
        $length = ($request->has('length')) ? $request->input('length') : 10; // meta length
        $start = ($request->has('start')) ? $request->start : 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns : []; // meta start
        if ($request->input('search')) {
            $search = ($request->input('search')['value'] != "All"
                and $request->input('search')['value'] != "Not trashed"
                and $request->input('search')['value'] != "Trashed") ?
                $request->input('search') : [];
            $filter = ($request->input('search')['value'] == "All"
                or $request->input('search')['value'] == "Trashed") ?
                $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
        }
        $data = $this->repo->ajaxPaginate($length, $start, $query, $filter, $search, $columns, $orders);

        return response()->json([
            'data' => DepartmentResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }

    public function getDepartment($id): \Illuminate\Http\JsonResponse
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function getAllDepartments()
    {
        $data = $this->repo->getModelClass()->all();
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DepartmentRequest $request)
    {
        try {
            return $this->repo->createDepartment([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'description' => $request->description,
            ]);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => 'Unknown error'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DepartmentRequest $request)
    {
        try {
            if ($this->repo->getModelClass()->findOrFail($request->id))
                return $this->repo->updateDepartment($request->id, [
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_ar,
                    'description' => $request->description,
                ]);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            return $this->repo->bulkDelete($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            return $this->repo->bulkRestore($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
}
