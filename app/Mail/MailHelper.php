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
        try{
            Mail::send(['raw' => $data['message']], ["name" => "Tahweel"], function($message) use($data) {
                $message->to($data["to"], "Tahweel Password Recover")->subject
                ('Tahweel Recover');
            });
            return true;
        }catch (\Exception $e) {
            return false;
        }
    }
}
