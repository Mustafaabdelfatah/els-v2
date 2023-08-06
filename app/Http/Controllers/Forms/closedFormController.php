<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignFormResource;
use App\Ldap\User;
use App\Repositories\Eloquent\AssignedFormsRepository;
use Illuminate\Http\Request;

class closedFormController extends Controller
{
    private $repo;

    public function __construct(AssignedFormsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request) {
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
        // get the names of the user's roles
        $user = \Auth::user();
        $roles = $user->getRoleNames()->toArray();
//        return response()->json($roles);// Returns a collection
        if (in_array('Employee',$roles)){
            $query['user_id'] = auth()->user()->id;
        }
        $query['status'] = 'active';
        $query = $this->repo->assignedAjaxPaginate($length, $start, $query, $filter, $search, $columns, $orders, ["user", "form", "form.children"], true);

        $query->whereHas('form', function ($query) use ($request) {
            $query->where('status', '=', 'closed');
            $query->orWhere('status', '=', 'rejected');
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

        if ($request->input('template_id', 0))
            $query->whereHas('form', function ($query) use ($request) {
                $query->where('template_id', '=', $request->template_id);
            });

        $data = $query->paginate($length);

        return response()->json([
            'data' => AssignFormResource::make($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }
}
