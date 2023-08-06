<?php


namespace App\Repositories\Eloquent;
use App\Models\AssignRequest;
use App\Models\RequestNote;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use App\Models\ReturnRequest as returnModification;
use App\Models\FormPageItemFill;
use App\Models\FormPageItem;
use App\Models\TemplatePageItem;
use App\Models\TemplatePage;
use App\Models\FormPage;
use App\Models\Template;
use Exception;

class AssignedFormsRepository extends BaseRepository
{
    public function model()
    {
        return AssignRequest::class;
    }
    public function getById($id)
    {
        return AssignRequest::where('id',$id)->first();
    }


//    public function getAssignedById($id)
//    {
//        return AssignRequest::where('id',$id)->first();
//    }

    public function assignNewRequest(array $data)
    {
        try {
            DB::beginTransaction();
            $assignRequest = AssignRequest::create($data);
            DB::commit();

            return $assignRequest;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
