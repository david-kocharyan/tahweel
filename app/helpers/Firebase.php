<?php


namespace App\helpers;

use App\Model\FcmToken;
use App\User;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Model\Notification as Notif;

class Firebase
{
    const ANDROID = 1;
    const IOS = 2;
    const ANDROID_ARR = 'android';
    const IOS_ARR = 'ios';

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    public static function send($tokens, string $notif, string $event = null, $event_id = null, $image = null, $type = null, $title = null, $link = null)
    {
        if (empty($tokens)) return;
        $firebase = new self();
        $data = array(
            "image" => $image,
            "title" => $title = null ? "notification" : $title,
            "body" => $notif,
            "category" => "click",
            'type' => $type,
            'link' => $link,
        );

        $data = User::with('tokensForAll')->whereIn('token', $tokens)->has('tokensForAll')->get()->pluck('tokensForAll.token', 'tokensForAll.os')->toArray();
        $result = array();
        foreach ($data as $d) {
            if ($d->os == Firebase::ANDROID && !empty($d->token)) {
                $result[Firebase::ANDROID_ARR][] = $d->token;
            } elseif ($d->os == Firebase::IOS && !empty($d->token)) {
                $result[Firebase::IOS_ARR][] = $d->token;
            }
        }

        dd($result);



        $config = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $notif,
                    ],
                    "category" => "link",
                ],
            ],
        ]);

        $message = CloudMessage::new()
            ->withData($data)
            ->withNotification(Notification::create($notif))
            ->withApnsConfig($config);



        $firebase->saveNotification($notif, $tokens, $type, $title, $link);
        if (is_array($tokens)) {
            $firebase->sendMulti($message, $tokens);
        } else {
            $firebase->sendSpecific($message, $tokens);
        }

    }

    private function sendMulti($message, $tokens)
    {
        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    private function sendSpecific($message, $token)
    {
        try {
            $message->withTarget("token", $token);
            $this->messaging->send($message);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    private function saveNotification($body, $tokens, $type, $title = null, $link = null)
    {
        $user = Auth::guard('api')->user() ?? Auth::guard('web')->user();  // The user who has sent the notification
        $name = $user->full_name ?? $user->name;
        if (is_array($tokens)) {
            foreach ($tokens as $key => $value) {
                $assignedToUser = User::find(FcmToken::where("token", $value)->first()->user_id);  // The user who receives the notification
                $notification = new Notif();
                $notification->title = $title ?? ($name . ($user->role == 1 ? " (Plumber)" : ($user->role == 2 ? " (Inspector)" : "")));
                $notification->body = $body;
                $notification->user_id = $assignedToUser->id;
                $notification->type = $type;
                $notification->link = $link;
                try {
                    $notification->save();
                } catch (\Exception $e) {

                }
            }
        } else {
            $assignedToUser = User::find(FcmToken::where("token", $tokens)->first()->user_id);  // The user who receives the notification
            $notification = new Notif();
            $notification->title = $title ?? ($name . ($user->role == 1 ? " (Plumber)" : ($user->role == 2 ? " (Inspector)" : "")));
            $notification->body = $body;
            $notification->user_id = $assignedToUser->id;
            $notification->type = $type;
            $notification->type = $link;
            try {
                $notification->save();
            } catch (\Exception $e) {

            }
        }
    }
}
