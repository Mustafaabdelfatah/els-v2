<?php

namespace App\Http\Controllers\Forms;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AssignRequest;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use App\Http\Resources\AssignFormResource;
use App\Repositories\Eloquent\AssignedFormsRepository;

class AssignFormController extends Controller
{
    private $repo;

    public function __construct(AssignedFormsRepository $repo)
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
        $length = ($request->input('length')) ? $request->input('length') : 10; // meta length
        $start = ($request->has('start')) ? $request->start: 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns: []; // meta start
        if($request->input('search')){
            $deleteFilter = ["All","Not trashed","Trashed"];
            $search = (!in_array($request->input('search')['value'],$deleteFilter) ) ?
                $request->input('search'):[];
            $filter = (in_array($request->input('search')['value'],$deleteFilter))?
                $request->input('search')['value'] : null;

        } else {
            $search = [];
            $filter = '';
        }
        $user = \Auth::user();
        $roles = $user->getRoleNames()->toArray();
        if (in_array('Employee',$roles)){
            $query['user_id'] = auth()->user()->id;
        }
        $query['status'] = 'active';
        $query = $this->repo->assignedAjaxPaginate($length, $start, $query, $filter, $search, $columns, $orders, ["user", "form", "form.children"], true);

        $query->whereHas('form', function ($query) use ($request) {
            $query->where('status', '!=', 'closed');
            $query->where('status', '!=', 'rejected');
        });

        if($request->input('search')['value'])
        {
            $query->whereHas('form', function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('search')['value'] . '%');
                });
            });
        }

        if ($request->input('department_id', 0))
            $query->whereHas('user.department', function ($query) use ($request) {
                $query->where('id', '=', $request->department_id);
            });

        if ($request->input('organization_id', 0))
            $query->whereHas('user.organization', function ($query) use ($request) {
                $query->where('id', '=', $request->organization_id);
            });

        if ($request->input('user_id', 0))
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('id', '=', $request->user_id);
            });

        if ($request->input('template_id', 0))
            $query->whereHas('form', function ($query) use ($request) {
                $query->where('template_id', '=', $request->template_id);
            });

        if ($request->input('status', 0))
            $query->whereHas('form', function ($query) use ($request) {
                $query->where('status', '=', $request->status);
            });

            $start_date = $request->input('start_date');
            $to_date = $request->input('to_date');
            if ($start_date && $to_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($start_date)->startOfDay())
                      ->whereDate('created_at', '<=', Carbon::parse($to_date)->endOfDay());
            }
            // return $query->where('form_id',13)->paginate(10);
            if ($request->input('priority', 0)){
                $return_request = ReturnRequest::where('priority',$request->input('priority'))->pluck('form_id')->toArray();
                // return $return_request;
                $query->whereIn('form_id', $return_request);
            }
        $data = $query->paginate($length);

        return response()->json([
            'data' => AssignFormResource::make($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssignRequest  $assignRequest
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        $data = $this->repo->getAssignedById($id);
//        try {
//            return response()->json($data);
//        } catch (Exception $e) {
//            return response()->json(['message' => 'Unknown error'], 500);
//        }
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssignRequest  $assignRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssignRequest  $assignRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssignRequest $assignRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssignRequest  $assignRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssignRequest $assignRequest)
    {
        //
    }
}
