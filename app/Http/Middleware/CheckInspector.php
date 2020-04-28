<?php

namespace App\Http\Middleware;

use App\helpers\ResponseHelper;
use App\Model\InspectionInspector;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckInspector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        $insp = InspectionInspector::where(["inspection_id" => $request->inspection, "inspector_id" => $user->id])->first();
        if(null == $insp) {
            return ResponseHelper::fail("You cannot access this resource", 403);
        }
        return $next($request);
    }
}
