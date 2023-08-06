<?php

namespace App\Services\Uploader;

class UploadService
{

    public function StoreFile($request)
    {
        if ($request->hasFile('file')) {
            if (!empty($request->input('folder'))) {
                $destination_path = 'public/' . $request->folder;
            } else {
                $destination_path = 'public/media';
            }
            $file = $request->file('file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($destination_path, $fileName);
            $path = url('/storage') . '/' . str_replace('public/', '', $path);

            return $fileName;
        }
    }
}
