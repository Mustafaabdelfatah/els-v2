<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivitylogResource;
use App\Repositories\Eloquent\ActivitylogsRepository;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;


class ActivitylogController extends Controller
{
    protected $repo;

    public function __construct(ActivitylogsRepository $repo)
    {
        $this->repo = $repo;
    }

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
        $length = ($request->has('lenght')) ? $request->lenght : 10; // meta length
        $start = ($request->has('start')) ? $request->start: 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns: []; // meta start
        $search = ($request->input('search')) ? $request->input('search') : [];
        $filter = $request->input('filter');
        $data = $this->repo->ajaxPaginate($length,$start, $query, $filter ,$search ,$columns ,$orders);
        return response()->json([
            'data' => ActivitylogResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int) $data->total(),
            'recordsTotal' => (int) $data->total(),
        ]);
    }
}
