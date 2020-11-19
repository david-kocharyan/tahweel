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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $role = User::ROLES;

//        $plumber = User::where(array('role' => 1, 'approved' => 1))->get();
//        $inspector = User::where(array('role' => 2, 'approved' => 1))->get();
//        $city = User::all();

        return view(self::FOLDER . ".index", compact('role', 'title', 'route'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function send(Request $request)
    {
        $request->validate([
            'role' => 'required|array|min:1',
            'message' => 'required',
            "title" => "required",
            "link" => "nullable|string"
        ]);

        if (count($request->role) > 1) {
            $tokens = User::with('tokensForAll')->has('tokensForAll')->get()->pluck('tokensForAll.token')->toArray();
        } else {
            $tokens = User::with('tokensForAll')->where('role', $request->role[0])->has('tokensForAll')->get()->pluck('tokensForAll.token')->toArray();
        }

        if ($request->link  != null){
            Firebase::send($tokens, $request->message, null, null, null, Notification::ADMIN_LINK_TYPE, $request->title, $request->link);
        }else{
            Firebase::send($tokens, $request->message, null, null, null, Notification::ADMIN_TYPE, $request->title);
        }

        return redirect(self::ROUTE);
    }
}
