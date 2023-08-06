<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Nmcs\Repositories\Eloquent\MailTemplateRepo;
use App\Http\Requests\JobRequest\BulkDeleteRequest;
use App\Http\Requests\Mail\MailTemplateStoreRequest;
use App\Http\Requests\Mail\MailTemplateUpdateRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\JobRequest\SearchResource;
use App\Http\Resources\MailTemplateResource;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class MailTemplateController extends Controller
{
    protected $depRepo;

    public function __construct(MailTemplateRepo $depRepo)
    {
        $this->depRepo = $depRepo;

        $this->middleware('permission:list-mails', ['only' => ['index']]);
        $this->middleware('permission:create-mails', ['only' => ['store']]);
        $this->middleware('permission:edit-mails', ['only' => ['update']]);
        $this->middleware('permission:delete-mails', ['only' => ['bulkDelete']]);
        $this->middleware('permission:restore-mails', ['only' => ['bulkRestore']]);
    }

    public function all()
    {
        return responseSuccess([
            'data' => MailTemplate::pluck('title', 'id'),
        ], 'data returned successfully');
    }
//
//    public function index(PaginateRequest $request)
//    {
//        $data = $this->depRepo->paginate($request);
//
//        return responseSuccess([
//            'data' => MailTemplateResource::collection($data),
//            'meta' => [
//                'total' => $data->total(),
//                'currentPage' => $request->offset,
//                'lastPage' => $data->lastPage()
//            ],
//        ], 'data returned successfully');
//    }

    public function index(PaginateRequest $request)
    {
        $input = $this->depRepo->inputs($request);
        $model = new MailTemplate();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->depRepo->whereOptions($request, $columns);
        }
        $data = $this->depRepo->newPaginate($input, $wheres);

        return responseSuccess([
            'data' => $input["resource"] == "all" ? MailTemplateResource::collection($data) : SearchResource::collection($data),
            'meta' => [
                'total' => $input["paginate"] ? $data->total() : $data->count(),
                'currentPage' => $input["offset"],
                'lastPage' => $input["paginate"] ? $data->lastPage() : 1,
            ],
        ], 'data returned successfully');
    }

    public function get($template)
    {
        $data = $this->depRepo->findOrFail($template);

        return responseSuccess([
            'data' => new MailTemplateResource($data),
        ], 'data returned successfully');
    }

    public function store(MailTemplateStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->depRepo->create([
                'title' => $request->title,
                'subject' => $request->subject,
                'body' => $request->body,
            ]);
            DB::commit();
            if ($data) {
                return responseSuccess(new MailTemplateResource($data), 'data saved successfully');
            } else {
                return responseFail('something went wrong');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseFail('something went wrong');
        }
    }

    public function update(MailTemplate $template, MailTemplateUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->depRepo->update([
                'title' => $request->title,
                'subject' => $request->subject,
                'body' => $request->body,
            ], $template);
            DB::commit();

            if ($data) {
                return responseSuccess(new MailTemplateResource($template->refresh()), 'data Updated successfully');
            } else {
                return responseFail('something went wrong');
            }
        } catch (\Exception $e) {
            return responseFail('something went wrong');
        }
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {

        $data = $this->depRepo->bulkDelete($request->ids);


        if ($data) {
            return responseSuccess([], 'data deleted successfully');
        } else {
            return responseFail('something went wrong');
        }

    }

    public function bulkRestore(BulkDeleteRequest $request)
    {

        $data = $this->depRepo->bulkRestore($request->ids);
        if ($data) {
            return responseSuccess([], 'data restored successfully');
        } else {
            return responseFail('something went wrong');
        }

    }

}
