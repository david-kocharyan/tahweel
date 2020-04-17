<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Inspection;
use App\Model\Phase;
use App\User;
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

    public function getInspections(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $status = !is_numeric($request->status) ? null : $request->status;
        $inspections = ($request->role == User::ROLES["plumber"] ? $this->getPlumberInspections($limit, $status) : $this->getInspectorInspections($limit, $status));
        return ResponseHelper::success($inspections, true);
    }

    private function getPlumberInspections($limit, $status = null)
    {
        $inspections = DB::table("inspections")
            ->selectRaw("inspections.id, 'project_name' as project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as inspector, CASE WHEN users.full_name IS NULL THEN 0 ELSE 1 END as hasInspector")
            ->leftJoin("phases", "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id")
            ->leftJoin("users", "users.id", "=", "inspection_inspectors.inspector_id")
            ->where(["inspections.plumber_id" => Auth::guard('api')->user()->id])
            ->groupBy("inspections.id", "phases.phase", "phases.status", "users.full_name", "address", "apartment");
        if(null != $status) {
            $inspections->where("phases.status", $status);
        }
        return $inspections->paginate($limit);
    }

    private function getInspectorInspections($limit, $status = null)
    {
        $inspections = DB::table("inspections")
            ->selectRaw("inspections.id, concat('project', inspections.id) as project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as inspector, CASE WHEN users.full_name IS NULL THEN 0 ELSE 1 END as hasInspector")
            ->leftJoin("phases", "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id")
            ->leftJoin("users", "users.id", "=", "inspection_inspectors.inspector_id")
            ->where(["inspections.plumber_id" => Auth::guard('api')->user()->id])
            ->groupBy("inspections.id", "phases.phase", "phases.status", "users.full_name", "address", "apartment");
        if(null != $status) {
            $inspections->where("phases.status", $status);
        }
        return $inspections->paginate($limit);
    }
}
