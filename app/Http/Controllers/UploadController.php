<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploaderRequest;
use App\Services\Uploader\UploadService;

class UploadController extends Controller
{
    public function store(UploaderRequest $request)
    {
        try {
            $file = new UploadService();
            $path = $file->StoreFile($request);
            if ($path) {
                return response()->json(['filename' => $path, 'message' => 'data uploaded successfully'], 200);
            } else {
                return response()->json(['message' => 'Unknown error'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unknown error' . $e->getMessage()], 500);
        }
    }
}
