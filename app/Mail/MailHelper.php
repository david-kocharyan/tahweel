<?php


namespace App\Mail;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function send($to, $message)
    {
        $data = array(
            "to" => $to,
            "message" => $message
        );
        Mail::send(['raw' => $data['message']], ["name" => "Tahweel"], function($message) use($data) {
            $message->to($data["to"], "Tahweel Password Recover")->subject
            ('Tahweel Recover');
//            $message->from('info@khatchkar.com', 'mykhatchkar.com');
        });
    }
}
