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
            $users = User::all();
        } else {
            $users = User::where('role', $request->role[0])->get();
        }

        dd($users);
//        $tokens = $users->tokens()->get()->pluck('token')->toArray();



//        Firebase::send($tokens, "Dear $user->full_name, You Have a New Inspection Request", null, null, null, Notification::INSPECTION_TYPE);

    }
}
