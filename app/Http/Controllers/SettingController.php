<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\Eloquent\SettingsRepo;
use App\Http\Requests\SettingGetRequest;
use App\Http\Requests\SettingUpdateRequest as SettingRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Http\Requests\TermsRequest;
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $repo;

    /**
     * Create a new controller instance.
     *
     * @param SettingsRepo $repo
     */

    public function __construct(SettingsRepo $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $logoFile = null;
        $appUrl = Setting::where('key','APP_URL')->first('value')->value;
        $appLogo = Setting::where('key','APP_LOGO')->first('value')->value;
        if($appLogo)
        {
            $logoFile = $appUrl.'/'.str_replace('public', 'storage', $appLogo);
            $remoteFile = $logoFile;
            $handle = @fopen($remoteFile, 'r');
            if(!$handle)
                $logoFile = null;
        }
        return response()->json(['data' => $this->repo->all()->groupBy('group'),'appUrl'=>$appUrl,'appLogo'=>$appLogo,'logoFile'=>$logoFile],200);
    }

    public function updateSettings(Request $request)
    {
        try {
            return $this->repo->updateRepoSetting($request->data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function terms()
    {
        return responseSuccess([
            'terms' => $this->settingRepo->getTerms()
        ], 200);
    }

    public function termsUpdate(TermsRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->settingRepo->updateTerms($request);
            DB::commit();
            return responseSuccess('data Updated successfully');
        } catch (\Exception $e) {
            return responseFail('something went wrong');
        }
    }

    public function generalSettings(SettingGetRequest $request)
    {
        return responseSuccess([
            $request->key => $this->settingRepo->generalSettings($request->key)
        ], 200);
    }

    public function generalSettingsUpdate(SettingUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->settingRepo->generalSettingsUpdate($request->key, $request->value);
            DB::commit();
            return responseSuccess('data Updated successfully');
        } catch (\Exception $e) {
            return responseFail('something went wrong');
        }
    }

    public function upload(Request $request)
    {
        $file = '';
        if ($request->hasFile('file')) {
            if (!empty($request->input('folder'))) {
                $destination_path = 'public/' . $request->folder;
            } else {
                $destination_path = 'public/media';
            }
            $image = $request->file('file');
            $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
            $path = $request->file('file')->storeAs($destination_path, $fileName);
            $path = url('/storage') . '/' . str_replace('public/', '', $path);
            $file =  $destination_path . '/' . $fileName;
        }
        return $this->repo->uploadImage($request->folder , $request->group , $request->key , $file);
    }

    public function getLoginSetting()
    {
        $logoFile = null;
        $appName = Setting::where('key','TITLE')->first('value')->value;
        $appDesc = Setting::where('key','DESCRIPTION')->first('value')->value;
        $appUrl = Setting::where('key','APP_URL')->first('value')->value;
        $appLogo = Setting::where('key','APP_LOGO')->first('value')->value;
        if($appLogo)
        {
            $logoFile = $appUrl.'/'.str_replace('public', 'storage', $appLogo);
            $remoteFile = $logoFile;
            $handle = @fopen($remoteFile, 'r');
            if(!$handle)
                $logoFile = null;
        }

        return response()->json(['appName'=>$appName,'appDesc'=>$appDesc,'appUrl'=>$appUrl,'appLogo'=>$appLogo,'logoFile'=>$logoFile]);
    }
}
