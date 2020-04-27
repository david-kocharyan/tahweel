<?php


namespace App\helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileUploadHelper
{
    public static function upload($data, array $allowedfileExtension, string $folder, bool $resize = false, int $width = 800, int $height = 800)
    {
        $images = [];
        if(is_array($data))
            foreach ($data as $image) {
                if (in_array("*", $allowedfileExtension) || in_array($image->getClientOriginalExtension(), $allowedfileExtension)) {
                    if ($resize) {
                        $image = self::resize($image, $folder, $width, $height);
                    } else {
                        $image = Storage::putFile($folder, new File($image), 'public');
                    }
                    $images[]["image"] = $image;
                }
            }
        else
            $images = Storage::putFile($folder, new File($data), 'public');
        return $images;
    }

    private static function resize($image, string $folder, int $width, int $height)
    {
        $img = Image::make($image);
        $fileName = uniqid("", true) . '.' . $image->getClientOriginalExtension();
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->stream();
        Storage::put($folder . '/' . $fileName, $img, 'public');
        return $folder . "/" . $fileName;
    }
}
