<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\City;
use App\Model\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function country()
    {
        $country = Country::selectRaw('id, name')->where('id', 1)->first();
        $resp = [
            'country' => $country,
        ];
        return ResponseHelper::success($resp);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function city(Request $request)
    {
        $city = City::selectRaw('id, name')->where('country_id', $request->country_id)->get();
        return ResponseHelper::success($city);
    }

}
