<?php


namespace App\helpers;


class QrGenerator
{

    const FOLDER = "qr";

    public static function generate($unique_id)
    {
        $path = 'uploads/'.self::FOLDER.'/'.$unique_id.".png";
        \QrCode::size(500)
            ->format('png')
            ->generate($unique_id, public_path($path));

        return ($path);
    }
}
