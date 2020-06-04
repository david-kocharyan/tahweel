<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $notifications = Notification::selectRaw("id, type, title, body, (extract(EPOCH from created_at) * 1000) as date")->where(["user_id" => Auth::guard('api')->user()->id, "active" => 1])->orderBy("id", "DESC")->paginate($limit);
        return ResponseHelper::success($notifications, true);
    }

    public function deleteNotification(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'id' => 'required|integer',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $notification = Notification::find($data["id"]);
        $notification->active = 0;
        $notification->save();

        return ResponseHelper::success(array());
    }
}
