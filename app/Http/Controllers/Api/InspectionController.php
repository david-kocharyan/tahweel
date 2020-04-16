<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Inspection;
use App\Model\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\helpers\FileUploadHelper;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller
{
    public function request(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'address' => 'required|max:191',
                'latitude' => 'required|max:191',
                'longitude' => 'required|max:191',
                'apartment' => 'max:191',
                'building_type' => 'required|max:1|integer',
                'issue_id' => 'required|integer',
                'comment' => 'max:3000',
                'images' => 'required|array',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        $images = FileUploadHelper::upload($request->images, ["*"], "inspections");

        DB::beginTransaction();
        $inspection = new Inspection();
        $inspection->address = $request->address;
        $inspection->latitude = $request->latitude;
        $inspection->longitude = $request->longitude;
        $inspection->apartment = $request->apartment;
        $inspection->building_type = $request->building_type;
        $inspection->issue_id = $request->issue_id;
        $inspection->comment = $request->comment;
        $inspection->plumber_id = Auth::guard('api')->user()->id;
        $inspection->save();

        $inspection->images()->createMany($images);

        $phase = new Phase(["phase" => 1, "status" => 1]);
        $inspection->phases()->save($phase);

        DB::commit();

        return ResponseHelper::success(array());
    }

    public function getInspections()
    {

    }
}
