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
                'id' => 'required|array',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $notification = Notification::whereIn('id', $data['id'])->get();
        if($notification->isEmpty()) {
            return ResponseHelper::fail("Wrong Notification Id Provided", 422);
        }

        Notification::whereIn('id', $data['id'])->update(array('active' => 0));
        return ResponseHelper::success(array());
    }

}
