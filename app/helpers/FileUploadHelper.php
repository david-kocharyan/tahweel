<?php


namespace App\helpers;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    public static function upload($data, array $allowedfileExtension, string $folder)
    {
        $images = [];
        foreach ($data as $image) {
            if(in_array("*", $allowedfileExtension) || in_array($image->getClientOriginalExtension(), $allowedfileExtension)) {
                $image = Storage::putFile($folder, new File($image), 'public');
                $images[]["image"] = $image;
            }
        }
        return $images;
    }
}
