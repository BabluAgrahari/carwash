<?php
if (!function_exists('singleFile')) {

function singleFile($file, $folder)
{
    if ($file) {
        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $destinationPath = $folder;
        $profileImage = date('YmdHis') . "." . $file->getClientOriginalName();
        $file->move($destinationPath, $profileImage);
        $fileName = $profileImage;
        return $fileName;
    }
    return response()->json(['invalid_file_format'], 422);
}
}

if (!function_exists('multipleFile')) {

    function multipleFile($files, $folder)
    {
        if ($files) {
            if (!file_exists($folder))
                mkdir($folder, 0777, true);
            foreach ($files as $file) {
                $destinationPath = $folder;
                $profileImage = date('YmdHis') . "." . $file->getClientOriginalName();
                $file->move($destinationPath, $profileImage);
               
                $fileName[] = $profileImage;
            }
           
            return $fileName;
        }
        return response()->json(['invalid_file_format'], 422);
    }
}
?>