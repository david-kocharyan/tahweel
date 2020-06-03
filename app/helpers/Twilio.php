<?php


namespace App\helpers;

use Twilio\Rest\Client;
use App\helpers\ResponseHelper;

class Twilio
{

    const SID = "AC62ca829966c24dfe21b3e22d86d5aa69";
    const TOKEN = "110cfd854de029ef6e2a223cb8e22e60";
    const UNDEFINED_NUMBER_STATUS = 400;
    const SUCCESS_STATUS = 201;

    public static function send(string $number, string $body)
    {
        $twilio = new Client(self::SID, self::TOKEN);
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
