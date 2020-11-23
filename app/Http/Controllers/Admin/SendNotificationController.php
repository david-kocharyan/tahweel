<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\Http\Controllers\Controller;
use App\Model\City;
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
        $plumber = User::where(array('role' => 1, 'approved' => 1))->get();
        $inspector = User::where(array('role' => 2, 'approved' => 1))->get();
        $city = City::all();
        return view(self::FOLDER . ".index", compact('title', 'route', 'plumber', 'inspector', 'city'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required',
            "title" => "required",
            "link" => "nullable|string"
        ]);
        $arr = array();

        if (isset($request->plumber) AND $request->plumber[0] == 0) {
            $plumber = User::where('role', User::ROLES['plumber'])->get()->pluck('id')->toArray();
            $arr = array_merge($arr, $plumber);
        } elseif (isset($request->plumber) AND $request->plumber[0] != 0) {
            foreach ($request->plumber as $key => $val) {
                $arr[] = intval($val);
            }
        }

        if (isset($request->inspector) AND $request->inspector[0] == 0) {
            $inspector = User::where('role', User::ROLES['inspector'])->get()->pluck('id')->toArray();
            $arr = array_merge($arr, $inspector);
        } elseif (isset($request->inspector) AND $request->inspector[0] != 0) {
            foreach ($request->inspector as $key => $val) {
                $arr[] = intval($val);
            }
        }

        if (isset($request->city) AND $request->city[0] == 0) {
            $city = City::all()->pluck('id')->toArray();
            $user_by_all_city = User::whereIn('city_id', $city)->get()->pluck('id')->toArray();
            $arr = array_merge($arr, $user_by_all_city);
        } elseif (isset($request->city) AND $request->city[0] != 0) {
            $user_by_city = User::whereIn('city_id', $request->city)->get()->pluck('id')->toArray();
            $arr = array_merge($arr, $user_by_city);
        }

        $arr = array_values(array_unique($arr));
        $data = User::with('tokensForAll')->whereIn('id', $arr)->has('tokensForAll')->get()->pluck('tokensForAll.token', 'tokensForAll.os')->toArray();

        if ($request->link != null) {
            Firebase::send($data, $request->message, null, null, null, Notification::ADMIN_LINK_TYPE, $request->title, $request->link);
        } else {
            Firebase::send($data, $request->message, null, null, null, Notification::ADMIN_TYPE, $request->title);
        }

        return redirect(self::ROUTE);
    }
}
