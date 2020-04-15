<?php


namespace App\helpers;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Firebase
{
    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    public static function send($tokens, string $notif, ?string $event = null, $event_id = null, $image = null)
    {
        $firebase = new self();

        $data = array(
            "image" => $image,
            "title" => "notification",
            "body" => $notif,
            "action" => $event,
        );
        $message = CloudMessage::new()
            ->withData($data)
            ->withNotification(Notification::create($notif));

        if(is_array($tokens)) {
            $firebase->sendMulti($message, $tokens);
        } else {
            $firebase->sendSpecific($message, $tokens);
        }

    }

    private function sendMulti($message, $tokens)
    {
        try{
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Exception $exception) {

        }
    }

    private function sendSpecific($message, $token)
    {
        try{
            $message->withTarget("token", $token);
            $this->messaging->send($message);
        } catch (\Exception $exception) {

        }
    }
}
