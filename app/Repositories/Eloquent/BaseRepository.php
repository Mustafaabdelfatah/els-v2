<?php


namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefined;
use App\Http\Requests\PaginateRequest;
use App\Models\AssignRequest;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements IBase, ICriteria
{

    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function getModelClass()
    {
        if (!method_exists($this, 'model')) {
            throw new ModelNotDefined();
        }
        return app()->make($this->model());
    }

    public function all()
    {
        return $this->model->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $result = $this->model->find($id);
        $result->update($data);
        return $result;
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);
        foreach ($criteria as $criterion) {
            $this->model = $criterion->apply($this->model);
        }
        return $this;
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function ajaxPaginate($length, $start, $wheres = [], $filter = '', $search = [], $columns = [], $orders = [], $with = [], $query = false)
    {
        if (count($with))
            $this->model = $this->model->with($with);

        if (count($wheres)) {
            foreach ($wheres as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1)
                        if (!is_int($key1))
                            $this->model = $this->model->where($key, $key1, $value1);
                } elseif (!is_int($key)) {
                    $this->model = $this->model->where($key, $value);
                }
            }
            // if ($request->has('targetId')) {
            //     $this->model = $this->model->where('id', $request->input('targetId'));
            // }
        }
        if ($filter == 'All') {
            $this->model = $this->model->withTrashed();
        }
        if ($filter == 'Trashed') {
            $this->model = $this->model->onlyTrashed();
        }

//        if($assign) {
//            $this->assign($assign);
//        }

        if (count($columns)) {

            $columnKey = \Arr::pluck($columns ,'data');
            foreach ($columnKey as $key => $column) {
                if ($column == 'Check' || $column == 'requests_count' ) {
                    unset($columnKey[$key]);
                }
            }
            $this->model = $this->model->select($columnKey);
        }

        if ($search)
            $this->model = $this->model->where(function($q) use ($columns ,$search) {
                foreach ($columns as $key => $column) {
                    if ($column['data'] == 'Check' || $column['data'] == 'requests_count') {
                        unset($column['data']);
                    }
                    $searchable = filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN);
                    if ($searchable) {
                        $q->orWhere($column['data'], 'LIKE', '%'.$search['value'].'%');
                        if ($column['data'] == 'user_id') {
                            $q->orWhereHas('user', function($query) use ($search){
                                $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                            });
                        }
                    }
                }
            });
//        Log::info($orders);
        if (count($orders)) {
           foreach ($orders as $column => $type) {
                $this->model = $this->model->orderBy($column, $type);
            }
        }

        if ($length == '*') {
            return $this->model->get();
        }

        if ($start)
            Paginator::currentPageResolver(function () use ($start, $length) {
                return $start / $length + 1;
            });

        if ($query)
            return $this->model;

        return $this->model->paginate($length);
    }

    public function assignedAjaxPaginate($length, $start, $wheres = [], $filter = '', $search = [], $columns = [], $orders = [], $with = [], $query = false)
    {
        if (count($with))
            $this->model = $this->model->with($with);

        if (count($wheres)) {
            foreach ($wheres as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1)
                        if (!is_int($key1))
                            $this->model = $this->model->where($key, $key1, $value1);
                } elseif (!is_int($key)) {
                    $this->model = $this->model->where($key, $value);
                }
            }
            // if ($request->has('targetId')) {
            //     $this->model = $this->model->where('id', $request->input('targetId'));
            // }
        }
        if ($filter == 'All') {
            $this->model = $this->model->withTrashed();
        }
        if ($filter == 'Trashed') {
            $this->model = $this->model->onlyTrashed();
        }

//        if($assign) {
//            $this->assign($assign);
//        }

        if (count($columns)) {

            $columnKey = \Arr::pluck($columns ,'data');
            foreach ($columnKey as $key => $column) {
                if ($column == 'Check' || $column == 'requests_count' ) {
                    unset($columnKey[$key]);
                }
            }
            $this->model = $this->model->select($columnKey);
        }

        if ($search)
            $this->model = $this->model->where(function($q) use ($columns ,$search) {
                foreach ($columns as $key => $column) {
                    if ($column['data'] == 'Check' || $column['data'] == 'requests_count') {
                        unset($column['data']);
                    }
                    $searchable = filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN);
//                    if ($searchable) {
//                        $q->orWhere($column['data'], 'LIKE', '%'.$search['value'].'%');
//                        if ($column['data'] == 'user_id') {
//                            $q->orWhereHas('user', function($query) use ($search){
//                                $query->where('name', 'LIKE', '%' . $search['value'] . '%');
//                            });
//                        }
//                    }
                }
            });
//        Log::info($orders);
        if (count($orders)) {
            foreach ($orders as $column => $type) {
                $this->model = $this->model->orderBy($column, $type);
            }
        }

        if ($length == '*') {
            return $this->model->get();
        }

        if ($start)
            Paginator::currentPageResolver(function () use ($start, $length) {
                return $start / $length + 1;
            });

        if ($query)
            return $this->model;

        return $this->model->paginate($length);
    }

    public function assign($assign = '')
    {
        if($assign) {
            if ($assign == 'Processing')
                $this->model = $this->model->where('status','processing');

            if ($assign == 'Rejected')
                $this->model = $this->model->where('status','rejected');

            if ($assign == 'pending')
                $this->model = $this->model->where('status','pending');
        }
    }
    public function newPaginate(array $input, array $wheres, $model = null, $query = false)
    {
        $currentPage = $input["offset"];
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $this->model = new $this->model;
        $table = $this->model->getTable();
        if ($input["deleted"]) {
            $this->model = $this->model->onlyTrashed();
        }
        if (count($wheres)) {
            foreach ($wheres as $where) {
                switch ($where[1]) {
                    case 'in':
                        $this->model = $this->model->whereIn("$table.$where[0]", $where[2]);
                        break;
                    default:
                        $this->model = $this->model->where("$table.$where[0]", $where[1], $where[2]);
                }
            }
            $this->model = $this->model->orderBy($input["field"], $input["sort"]);

            if ($query)
                return $this->model;

            return $input["paginate"] ? $this->model->paginate($input["limit"]) : $this->model->get();
        }
        $this->model = $this->model->orderBy($input["field"], $input["sort"]);

        if ($query)
            return $this->model;

        return $input["paginate"] ? $this->model->paginate($input["limit"]) : $this->model->get();
    }

    public function bulkDelete(array $ids, $softDeleteCheck=true)
    {
        if ($softDeleteCheck==false) {
            $allRows = $this->model::withTrashed()->find($ids);
        }
        else {
            $allRows = $this->model::find($ids);
        }

        foreach ($allRows as $row) {
            if ($softDeleteCheck==false && $row->trashed()) {
                $row->forceDelete();
            } else {
                $row->delete();
                if (isset($row->active)) {
                    $row->active = false;
                    $row->save();
                }
            }
        }
        return true;
    }

    public function bulkTrash()
    {
        $allRows = $this->model::onlyTrashed()->get();
        return $allRows;
    }

    public function bulkRestore(array $ids)
    {
        $allRows = $this->model::onlyTrashed()->find($ids);
        foreach ($allRows as $row) {
            $row->restore();
            if (isset($row->active)) {
                $row->active = true;
                $row->save();
            }

        }
        return true;
    }
}
