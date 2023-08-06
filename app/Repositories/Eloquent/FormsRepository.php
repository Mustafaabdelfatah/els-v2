<?php


namespace App\Repositories\Eloquent;

use File;
use Exception;
use App\Models\Form;
use App\Models\User;
use http\Env\Response;
use App\Models\Setting;
use App\Models\FormPage;
use App\Models\Template;
use App\Models\CloseFile;
use App\Models\RequestFile;
use App\Models\RequestNote;
use Illuminate\Support\Str;
use App\Models\FormPageItem;
use App\Models\TemplatePage;
use App\Models\UploadedFile;
use App\Models\AssignRequest;
use App\Models\ApproveRequest;
use App\Models\FormPageItemFill;
use App\Models\TemplatePageItem;
use Illuminate\Support\Facades\DB;
use App\Models\File as FileRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MailNotification;
use App\Repositories\Contracts\IPforms;
use Illuminate\Support\Facades\Storage;
use App\Models\ReturnRequest as returnModification;

class FormsRepository extends BaseRepository implements IPforms
{
    public function model()
    {
        return Form::class;
    }

    public function getById($id)
    {
        DB::connection()->enableQueryLog();
        // Your query here...
        // $test = Form::with([ 'closed.files'])->where('id', $id)->first();

        // return $test;

        // $test = Form::with('closed.files')->where('id', $id)->first();

        // return $test;
        // return $this->getModelClass()->with(['closed','closed.files'])->where('id', $id)->first();
        $form = $this->getModelClass()->with(
            'parent_form',
            'parent_form.user',
            'parent_form.pages',
            'parent_form.pages.items',
            'parent_form.pages.items.filling',
            'parent_form.assignedRequests',
            'parent_form.assignedRequests.user',
            'parent_form.assignedRequests.assigner',
            'parent_form.amendmentRequest',
            'parent_form.amendmentRequest.user',
            'user',
            'template',
            'pages',
            'pages.items',
            'pages.items.filling',
            'assignedRequests',
            'assignedRequests.user',
            'assignedRequests.assigner',
            'amendmentRequest',
            'amendmentRequest.uploadedFiles',
            'amendmentRequest.user',
            'amendmentRequest.rejectedFiles',
            'approve',
            'approve.uploadedFiles',
            'approve.user',
            'closed',
            'requestNotes',
            'closed.files'
        )->where('id', $id)->first();
        return $form->histories();
    }

    public function lastStatus($formId)
    {
        return Form::latest()->where('id', $formId)->orWhere('parent', $formId)->first()->status;
    }

    public function getAssignedById($id)
    {
        return AssignRequest::where('id', $id)->first();
    }


    public function create_form_fills($userId, $tempId, $data, $fileName)
    {
        $usersHasPreview = array();
        $ins = array();
        DB::beginTransaction();
        try {

            $user = User::find($userId);
            $tamplate = Template::find($tempId);
            $form = Form::create([
                'user_id' => $userId,
                'name' => $tamplate->name,
                'template_id' => $tamplate->id,
                'organization_id' => $user->organization_id,
            ]);
             foreach ($data as $field => $page) {
                $tempPage = TemplatePage::find($page->id);
                $form_page = FormPage::create([
                    'title' => $tempPage->title,
                    'form_id' => $form->id,
                ]);
                foreach ($page->items as $item) {
                    $getOldItem = TemplatePageItem::find($item->id);
                    $itemForm = FormPageItem::create([
                        "type" => $getOldItem->type,
                        "label" => $getOldItem->label,
                        "enabled" => $getOldItem->enabled,
                        "required" => $getOldItem->required,
                        "website_view" => $getOldItem->website_view,
                        "notes" => $getOldItem->notes,
                        "width" => $getOldItem->width,
                        "height" => $getOldItem->height,
                        "childList" => $getOldItem->childList,
                        "form_page_id" => $form_page->id,
                    ]);
                    if ($item->type == 'file') {
                        if (!empty($item->value))
                        {
                            $item->value = uploadFile($item->value, 'public/media/requests', '', $fileName,$item->file_name);
                            $item->file_name = $item->file_name;
                        }
                    }
                    $ins[] = FormPageItemFill::create([
                        'file_name' => (!empty($item->file_name)) ? $item->file_name : '' ,
                        'value' => (!empty($item->value)) ? str_replace('public/', '', $item->value) : '',
                        'form_page_item_id' => $itemForm->id,
                        'user_id' => $userId
                    ]);
                }
            }
            $userData = [];
            $message = User::find($userId)->name . '  Filled A New Form';
            $page = Setting::where('key', 'MAIL_NOTIFY_NEW_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    Auth::user()->name,
                    Auth::user()->email,
                    $form->id,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $form->id . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>",
                ],
                $page
            );

            foreach (user::select('id', 'name', 'email')->get() as $userObj) {
                if(in_array('Root', $userObj->getRoleNames()->toArray()))
                {
                    try{
                        $userObj->notify(new MailNotification($page, ['user' => Auth::user()], $message));
                    }catch(\Exception $e){
                        //
                    }
                }
                else{
                    if ($userObj->organization_admin()->count())
                    {
                        if(in_array($user->organization_id, $userObj->organization_admin()->pluck('organization_id')->toArray()))
                        {
                            try{
                                $userObj->notify(new MailNotification($page, ['user' => Auth::user()], $message));
                            }catch(\Exception $e)
                            {
                                //
                            }
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['data' => $form, 'Message' => 'success Answer', 'code' => 200], 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function assignNewRequest(array $data)
    {

        DB::beginTransaction();
        try {
            $assignRequest = AssignRequest::create($data);

            $user = User::where('id', $data['user_id'])->first(); //get user
            $form_user = User::where('id', $data['user_id'])->first();
            $message = 'You have a new request from  requester';
            $page = Setting::where('key', 'MAIL_NOTIFY_ASSIGNED_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    $form_user->name,
                    $form_user->email,
                    $assignRequest->form_id,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $assignRequest->form_id . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>",
                ],
                $page
            );
            try{
                $form_user->notify(new MailNotification($page, ['user' => $form_user], $message)); //send mail
            }catch (Exception $e) {

            }

            DB::commit();
            return $assignRequest;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectRequest(array $data)
    {
        try {
            DB::beginTransaction();
            $rejectRequest = RequestNote::create($data);
            DB::commit();

            return $rejectRequest;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function approve($request,$parentFormId)
    {
        $files = [];
        $requestFiles = $request['files'] ?? [];
        DB::beginTransaction();
            try {
                $data = array();
                $request['moreInfoStatus'] = ($request['moreInfoStatus'] == 'true') ? 1 : 0;
                $request['secret'] = ($request['secret'] == 'true') ? 1 : 0;

                if ($requestFiles) {
                    foreach ($requestFiles as $fileData) {
                        $fileName = $this->generateUniqueFileName($fileData);
                        $filePath = 'media/approved_files/' . $fileName;

                        $file = explode(',',$fileData)[1];
                        $fileDataDecode = base64_decode($file);

                        try {
                            Storage::disk('public')->put($filePath, $fileDataDecode);
                            $files[] = [
                                'file' => $filePath,
                                'name' => $fileName,
                            ];
                        } catch (Exception $e) {
                            // Handle the error or throw an exception
                            throw new Exception('Error saving file: ' . $e->getMessage());
                        }
                    }
                }

                $form = $this->model->where('id', $request['form_id'])->first();
                $user = User::find($form->user_id);
                if($request['expected_amount'] > 0)
                {
                    $update = $form->update(['status' => $request['status']]);

                    $request['workers'] = ($request['workers'] == 'true') ? 1 : 0;
                    $request['commercial'] = ($request['commercial'] == 'true') ? 1 : 0;
                    $request['general'] = ($request['general'] == 'true') ? 1 : 0;
                    $request['administrative'] = ($request['administrative'] == 'true') ? 1 : 0;
                    $request['executive'] = ($request['executive'] == 'true') ? 1 : 0;

                    $form = ApproveRequest::create([
                        'user_id' => auth()->id(),
                        'form_id' => $request['form_id'],
                        'workers'=> $request['workers'],
                        'commercial'=> $request['commercial'],
                        'general'=> $request['general'],
                        'administrative'=> $request['administrative'],
                        'executive'=> $request['executive'],
                        'expected_amount'=> $request['expected_amount'],
                        'procedureType'=> $request['procedureType'],
                        'Priority' => $request['priority'],
                        'end_date' => $request['end_date'],
                        'informationText' => $request['informationText'],

                    ]);
                    $approveFiles = [];
                    foreach ($files as $file) {
                        // Create the uploaded file record
                        $uploadedFile = UploadedFile::create([
                            'name' => $file['name'],
                            'file' => $file['file'],
                            'uploadable_type' => ApproveRequest::class,
                            'uploadable_id' => $form->id,
                        ]);
                        $approveFiles[] = $uploadedFile;
                    }
                }
                else{
                    $update = $form->update(['status' => $request['status']]);
                }
                $message = 'your request has been ' . $request['status'];
                $page = Setting::where('key', 'MAIL_NOTIFY_CHANGE_REQUEST_STATUS')->first('value')->value;

                $page = str_replace(
                    [
                        '[[name]]',
                        '[[email]]',
                        '[[status]]',
                        '[[requestNo]]',
                        '[[mailLink]]'
                    ],
                    [
                        Auth::user()->name,
                        Auth::user()->email,
                        $request['status'],
                        $parentFormId,
                        "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $request['form_id'] . "'>" .
                        Setting::where('key', 'MAIL_Link')->first('value')->value.
                        "</a>",
                    ],
                    $page
                );

                try{
                    $user->notify(new MailNotification($page, ['user' => Auth::user()], $message)); //send mail assigned
                } catch (Exception $e) {
                }
                DB::commit();
                return $form;
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
    }
    public function changeStatus($id, $status, $comment,$parentFormId)
    {
        DB::beginTransaction();
        try {
            $form = $this->model->where('id', $id)->first();
            $user = User::find($form->user_id);
            if($status === 'rejected')
            {
                $closed = RequestFile::create([
                    'comment' => 'rejected',
                    'form_id' => $parentFormId,
                    'user_id' => Auth::user()->id,
                ]);
            }
            $update = $form->update(['status' => $status, 'comment' => $comment]);
            $message = 'your request has been ' . $status;
            $page = Setting::where('key', 'MAIL_NOTIFY_CHANGE_REQUEST_STATUS')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[status]]',
                    '[[requestNo]]',
                ],
                [
                    Auth::user()->name,
                    Auth::user()->email,
                    $status,
                    $parentFormId
                ],
                $page
            );

            $user->notify(new MailNotification($page, ['user' => Auth::user()], $message)); //send mail assigned
            DB::commit();
            return $form;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function approveSecretData($id,$parentFormId)
    {

        DB::beginTransaction();
        try {
            $returned_form = returnModification::where('id', $id)->first();
            $user = User::find($returned_form->user_id);
            $form = Form::where('id',$returned_form->form_id)->first();
            $form->update(['status' => 'processing']);
            $update = $returned_form->update(['approve_secret' => 1]);
            $message = 'your request has been ';
            $page = Setting::where('key', 'MAIL_NOTIFY_APPROVE_SECRET_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    Auth::user()->name,
                    Auth::user()->email,
                    $parentFormId,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $returned_form->form_id . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>",
                ],
                $page
            );

            $form->user->notify(new MailNotification($page, ['user' => Auth::user()], $message));
            DB::commit();
            return $returned_form;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function returnRequest($request,$parentFormId)
    {

        $message = '';
        $files = [];
        $requestFiles = $request['files'] ?? [];

        DB::beginTransaction();
        $user_id = form::where('id', $request['form_id'])->first()->user_id;
        $user = User::find($user_id);
        try {
            $data = array();
            $request['moreInfoStatus'] = ($request['moreInfoStatus'] == 'true') ? 1 : 0;
            $request['secret'] = ($request['secret'] == 'true') ? 1 : 0;
            if ($requestFiles) {
                foreach ($requestFiles as $fileData) {
                    $fileName = $this->generateUniqueFileName($fileData);
                    $filePath = 'media/returned_files/' . $fileName;

                    $file = explode(',',$fileData)[1];
                    $fileDataDecode = base64_decode($file);

                    try {
                        Storage::disk('public')->put($filePath, $fileDataDecode);
                        $files[] = [
                            'file' => $filePath,
                            'name' => $fileName,
                        ];
                    } catch (Exception $e) {
                        // Handle the error or throw an exception
                        throw new Exception('Error saving file: ' . $e->getMessage());
                    }

                    // Storage::disk('public')->put($filePath, $fileData);
                    // $files[] = [
                    //     'file' => $filePath,
                    //     'name' => $fileName,
                    // ];
                }
            }

            if($request['comment']){
                $returnModification = ReturnModification::create([
                    'form_id' => $request['form_id'],
                    'is_secret' => $request['secret'],
                    'approve_secret' => 0,
                    'informationText' => $request['informationText'],
                    'Priority' => $request['Priority'],
                    'end_date' => $request['end_date'],
                    'user_id' => auth()->id(),
                    'type' => 'reject',
                    'comment' => $request['comment'],
                    'status' => ($request['secret'] == 1) ? 0 : 1,
                ]);
                Form::where('id', $request['form_id'])->update(['status' => 'rejected']);
                $returnModFiles = [];
                foreach ($files as $file) {
                    // Create the uploaded file record
                    $uploadedFile = UploadedFile::create([
                        'name' => $file['name'],
                        'file' => $file['file'],
                        'uploadable_type' =>'Rejected',
                        'uploadable_id' => $returnModification->id,
                    ]);
                    $returnModFiles[] = $uploadedFile;
                }
                $message = 'your request has been Rejected';
            }else{
                $returnModification = ReturnModification::create([
                    'form_id' => $request['form_id'],
                    'is_secret' => $request['secret'],
                    'approve_secret' => 0,
                    'informationText' => $request['informationText'],
                    'Priority' => $request['Priority'],
                    'end_date' => $request['end_date'],
                    'user_id' => auth()->id(),
                    'status' => ($request['secret'] == 1) ? 0 : 1,
                ]);
                Form::where('id', $request['form_id'])->update(['status' => 'returned']);
                $returnModFiles = [];
                foreach ($files as $file) {
                    // Create the uploaded file record
                    $uploadedFile = UploadedFile::create([
                        'name' => $file['name'],
                        'file' => $file['file'],
                        'uploadable_type' => ReturnModification::class,
                        'uploadable_id' => $returnModification->id,
                    ]);
                    $returnModFiles[] = $uploadedFile;
                }
                if ($request['secret'] == 1) {
                    $message = 'there is secret data needed';
                } else {
                    $message = 'need more information';
                }
            }

            $page = Setting::where('key', 'MAIL_NOTIFY_RETURNED_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    Auth::user()->name,
                    Auth::user()->email,
                    $parentFormId,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $returnModification->form_id . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>",
                ],
                $page
            );
            try{
                $user->notify(new MailNotification($page, ['user' => Auth::user()], $message));
            }catch (Exception $e) {
                // DB::rollBack();
                // throw $e;
            }
            DB::commit();
            return $returnModification;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    private function generateUniqueFileName($originalFileName)
    {

//        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
//        return $extension;

        $extension = explode('/', mime_content_type($originalFileName))[1];
        if($extension === 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            $extension = 'xlsx';
        elseif ($extension === 'octet-stream')
            $extension = 'docx';

        return uniqid() . '_' . Str::random(8) . '.' . $extension;
    }



    private function getFileExtensionFromMimeType($fileData)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($fileData);
        $extension = File::extension($mimeType);
        return $extension;
    }
    public function reviewFormsWithTracked()
    {
        try {
            // get user perrmition array
            $this->repo->getById(auth::user());
            return auth()->user();
//            return $this->getModelClass()->with('');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateFills($userId, $formId, $data, $parentFormId, $fileName)
    {
        $usersHasPreview = array();
        $ins = array();
        DB::beginTransaction();
        try {

            $user = User::find($userId);
            $form = Form::find($formId);
            $newForm = Form::create([
                'user_id' => $userId,
                'name' => $form->name,
                'template_id' => $form->template_id,
                'organization_id' => $user->organization_id,
                'status' => 'processing',
                'parent' => $formId,
            ]);
            $assignedForm = AssignRequest::where('form_id', $formId)->update(['status' => 'deleted']);
            $assigned = AssignRequest::where('form_id', $formId)->latest()->first();
            $assignedUser = User::find($assigned->user_id);
            $createAssignedForm = AssignRequest::create([
                'form_id' => $newForm->id,
                'user_id' => $assigned->user_id,
                'date' => $assigned->date,
                'assigner_id' => $assigned->assigner_id,
                'status' => 'active',
            ]);
            foreach ($data as $field => $page) {
                $oldFormPage = FormPage::find($page->id);
                $form_page = FormPage::create([
                    'title' => $oldFormPage->title,
                    'form_id' => $newForm->id,
                ]);
                foreach ($page->items as $item) {
                    $oldItem = FormPageItem::find($item->id);
                    $itemForm = FormPageItem::create([
                        "type" => $oldItem->type,
                        "label" => $oldItem->label,
                        "enabled" => $oldItem->enabled,
                        "required" => $oldItem->required,
                        "website_view" => $oldItem->website_view,
                        "notes" => $oldItem->notes,
                        "width" => $oldItem->width,
                        "height" => $oldItem->height,
                        "childList" => $oldItem->childList,
                        "form_page_id" => $form_page->id,
                    ]);

                    if ($item->type == 'file') {
                        if (!empty($item->value))
                        {
                            $item->filling->value = uploadFile($item->value, 'public/media/requests', '', $fileName,$item->file_name);
                            $item->filling->file_name = $item->file_name;
                        }
                    }
                    $ins[] = FormPageItemFill::create([
                        'value' => (!empty($item->filling->value)) ? str_replace('public/', '', $item->filling->value) : '',
                        'file_name' => (!empty($item->filling->file_name)) ? $item->filling->file_name : '',
                        'form_page_item_id' => $itemForm->id,
                        'user_id' => $userId
                    ]);

//                    $ins[] = FormPageItemFill::create([
//                        'value' => (!empty($item->filling->value)) ? $item->filling->value : '',
//                        'file_name' => (!empty($item->filling->file_name)) ? $item->filling->file_name : '',
//                        'form_page_item_id' => $itemForm->id,
//                        'user_id' => $userId
//                    ]);
                }
            }
            // edit return request for response user
            returnModification::where('form_id', $formId)->update(['status' => 0]);
            $page = Setting::where('key', 'MAIL_NOTIFY_EDIT_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    $user->name,
                    $user->email,
                    $parentFormId,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $newForm->id . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>"
                ],
                $page
            );

            // foreach (user::select('id', 'name', 'email')->get() as $userObj) {
            //     $Permissions = $userObj->getAllPermissions();
            //     $userPermission = \Arr::pluck($Permissions, 'name');
            //     if (in_array('review', $userPermission))
            //         $userObj->notify(new MailNotification($page, ['user' => $assignedUser], 'Edit request'));
            // }
            $assignedUser->notify(new MailNotification($page, ['user' => $assignedUser], 'Edit request'));
            DB::commit();
            return response()->json(['data' => $ins, 'Message' => 'success Answer', 'code' => 200], 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function historyOfForm($formId)
    {
        $history = Form::find($formId)->with('user', 'pages', 'pages.items', 'pages.items', 'pages.items.filling', 'amendmentRequest')
            ->where('id', $formId)
            ->get();
        return $history;
    }

    public function UpdateValue($form_id, $value = null)
    {
        DB::beginTransaction();
        try {
            returnModification::where('form_id', $form_id)->update([
                'value' => $value,
                'status' => 0
            ]);
            DB::commit();
            return response()->json(['message' => 'updated value from requester successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function closeRequest($data = [],$parentFormId)
    {
        DB::beginTransaction();
        try {
            $closed = RequestFile::create([
                'comment' => $data['comment'],
                 'form_id' => $data['form_id'],
                'company_judgment' => $data['company_judgment'],
                'type_of_judge' => $data['type_of_judge'],
                'case_date' => $data['case_date'],
                'case_number' => $data['case_number'],
                'expected_result' => $data['expected_result'],
                'user_id' => Auth::user()->id,
            ]);
            // dd(count($data['files']));

            if (!empty($data['files']) && count($data['files']) > 0) {
                foreach ($data['files'] as $fileKey => $file) {
                    CloseFile::create([
                        'request_file_id' => $closed->id,
                        'file' => $file,
                        'fileName' => $data['fileNames'][$fileKey]
                    ]);
                }
            }
            if (!empty($data['litigation_files']) && count($data['litigation_files']) > 0) {
                foreach ($data['litigation_files'] as $fileKey => $file) {
                    CloseFile::create([
                        'request_file_id' => $closed->id,
                        'file' => $file,
                        'fileName' => $data['litigationFileNames'][$fileKey]
                    ]);
                }
            }
            $form = Form::where('id', $data['form_id'])->update(['status' => 'closed']);
            $user_id = Form::where('id', $data['form_id'])->first()->user_id;
            $user = User::find($user_id);
            $returnedForm = returnModification::where('form_id', $data['form_id'])->first();
            if ($returnedForm)
                $returnedForm->update(['status' => 0]);
            DB::commit();
            $page = Setting::where('key', 'MAIL_NOTIFY_CLOSED_REQUEST')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[requestNo]]',
                    '[[mailLink]]'
                ],
                [
                    Auth::user()->name,
                    Auth::user()->email,
                    $parentFormId,
                    "<a target='_blank' href='" . Setting::where('key', 'MAIL_Link')->first('value')->value . '/request-show/#id=' . $data['form_id'] . "'>" .
                    Setting::where('key', 'MAIL_Link')->first('value')->value.
                    "</a>"
                ],
                $page
            );
            $user->notify(new MailNotification($page, ['user' => Auth::user()], 'Your request is closed by ' . Auth::user()->name));
            return response()->json(['data' => $data, 'message' => 'close request successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
