<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\User;
use Illuminate\Http\Request;

class SendNotificationController extends Controller
{
    const FOLDER = "admin.sendNotif";
    const TITLE = "Send Notification";
    const ROUTE = "/admin/send-notification";

    public function index()
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $role = User::ROLES;
        return view(self::FOLDER . ".index", compact('role', 'title', 'route'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'role' => 'required|array|min:1',
            'message' => 'required',
            "title" => "required"
        ]);

        if (count($request->role) > 1) {
            $tokens = User::with('tokensForAll')->has('tokensForAll')->get()->pluck('tokensForAll.token')->toArray();
        } else {
            $tokens = User::with('tokensForAll')->where('role', $request->role[0])->has('tokensForAll')->get()->pluck('tokensForAll.token')->toArray();
        }

        Firebase::send($tokens, $request->message, null, null, null, Notification::ADMIN_TYPE, $request->title);
        return redirect(self::ROUTE);
    }
}
