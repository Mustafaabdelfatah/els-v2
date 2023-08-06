<?php


use App\Exceptions\PlanException;

if (!function_exists('abort_plan')) {
    function abort_plan()
    {
        throw new PlanException();

    }
}
if (!function_exists('abort_plan_if')) {
    function abort_plan_if($abort = false)
    {
        if ($abort) {
            abort_plan();
        }
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $path = 'media', $disk = 'public', $fileName = '',$nameWithExt = '')
    {
        if ($file) {
            $data = $file;

            if($nameWithExt)
                $extension = explode('.', $nameWithExt)[1];
            else
                $extension = explode('/', explode(':', \substr($data, 0, \strpos($data, ';')))[1])[1];   // .jpg .png .pdf

                // $replace = substr($data, 0, strpos($data, ',') + 1);
                // $base64 = str_replace($replace, '', $data);
                // $base64 = str_replace(' ', '+', $base64);
            
                // // Decode and save as TXT file
                // $decodedData = base64_decode($base64);
               
                // file_put_contents('test.txt', $decodedData);
            $replace = \substr($data, 0, \strpos($data, ',') + 1);

            $base64 = \str_replace($replace, '', $data);

            $base64 = \str_replace(' ', '+', $base64);

            // return $replace.$base64;

            if (!empty($fileName))
                $imageName = str_replace(' ', '_', \Str::random(10) . '_' . date('d-m-Y H-i-s') . '.' . $extension);
            else
                $imageName = \Str::random(10) . '.' . $extension;

               
            $res = \Storage::disk($disk)->put($path . '/' . $imageName, base64_decode($base64));
            if ($res)
                return $path . '/' . $imageName;
            else
                return '';
        }
    }
}
