<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $notifications = Notification::selectRaw("id, type, title, body, (extract(EPOCH from created_at) * 1000) as date")->where(["user_id" => Auth::guard('api')->user()->id, "active" => 1])->orderBy("id", "DESC")->paginate($limit);
        $notifications = Notification::selectRaw("id, type, title, body, (extract(EPOCH from created_at) * 1000) as date")->paginate($limit);
        return ResponseHelper::success($notifications, true);
    }
}
