<?php


namespace App\helpers;


class QrGenerator
{

    const FOLDER = "qr";

    public static function generate($unique_id, $full_name, $phone = null)
    {
        $path = 'uploads/' . self::FOLDER . '/' . $unique_id . ".png";
        \QrCode::size(500)
            ->format('png')
            ->generate($unique_id . ", " . $full_name . ", " . $phone, public_path($path));

        return ($path);
    }
}
