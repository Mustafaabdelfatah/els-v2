<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\SettingUpdateRequest as SettingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ISettings;


class SettingsRepo extends BaseRepository implements ISettings
{
    public function model()
    {
        return Setting::class;
    }

    public function all()
    {
        return Setting::where('editable', true)->get();
    }

    public function updateRepoSetting($data, $id = 0)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $item = (object)$item;
                DB::table('settings')->updateOrInsert(
                    ['id' => $item->id],
                    ['value' => $item->value]
                );
            }
            $envdata = '';
            $file = fopen(app()->environmentFilePath(), 'r');
            foreach (Setting::all() as $setting) {
                if (!$setting->isEnv) continue;
                if (!$setting->editable)
//                    $envdata .= $setting->key . "=" . ($_ENV[$setting->key] ?? '') . PHP_EOL;
                    $envdata .= $setting->key . "=" . $setting->value . PHP_EOL;
                else {
                    if (!in_array($setting->value, ['true', 'false']))
                        $setting->value = '"' . $setting->value . '"';
                    $envdata .= $setting->key . "=" . $setting->value . PHP_EOL;
                }
            }
            $file = fopen(app()->environmentFilePath(), 'w');
            fwrite($file, $envdata);
            fclose($file);
                DB::commit();
            return response()->json(['data' => $data,'message'=>'success','code'=>200],200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTerms()
    {
        return Setting::where('key', 'terms')->first();
    }

    public function updateTerms($request)
    {
        $terms = Setting::where('key', 'terms')->firstOrNew();
        $terms->key = 'terms';
        $terms->value = $request->terms;
        $terms->group = 'terms';
        $terms->user_id = auth()->id();
        $terms->save();

        return $terms;
    }

    public function generalSettings($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function generalSettingsUpdate($key, $value)
    {
        $terms = Setting::where('key', $key)->firstOrNew();
        $terms->key = $key;
        $terms->value = $value;
        $terms->group = 'mails';
        // $terms->user_id = auth()->id();
        $terms->save();

        return $terms;
    }

    public function uploadImage($folder = "settings",$group = '' , $key = '', $file){
         Setting::where('key', $key)->update([
            'value' => $file
        ]);
        return Setting::where('key', $key)->first();
    }

}
