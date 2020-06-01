<?php


namespace App\helpers;

use Twilio\Rest\Client;
use App\helpers\ResponseHelper;

class Twilio
{

    const SID = "ACe6730449d7c971922523090d96726905";
    const TOKEN = "eb06527636c0da8f426d767a0cb41116";
    const UNDEFINED_NUMBER_STATUS = 400;
    const SUCCESS_STATUS = 201;

    public static function send(string $number, string $body)
    {
        $twilio = new Client(self::SID, self::TOKEN);
        try {
            $twilio->messages
                ->create($number, // to
                    array(
                        "from" => "+12082037299",
                        "body" => $body
                    )
                );
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }
}
