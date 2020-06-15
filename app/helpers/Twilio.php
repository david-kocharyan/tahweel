<?php


namespace App\helpers;

use Twilio\Rest\Client;
use App\helpers\ResponseHelper;

class Twilio
{

    const UNDEFINED_NUMBER_STATUS = 400;
    const SUCCESS_STATUS = 201;

    public static function send(string $number, string $body)
    {
        $twilio = new Client(env("TWILIO_SID"), env("TWILIO_TOKEN"));
        try {
            $twilio->messages
                ->create($number, // to
                    array(
                        "from" => "+15109240006",
                        "body" => $body
                    )
                );
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }
}
