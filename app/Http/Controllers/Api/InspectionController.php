<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Inspection;
use App\Model\Phase;
use App\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\helpers\FileUploadHelper;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = URL::to('/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function request(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'address' => 'required|max:191',
                'latitude' => 'required|max:191',
                'longitude' => 'required|max:191',
                'floor' => 'max:191',
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
        $inspection->floor = $request->floor;
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInspections(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $status = !is_numeric($request->status) ? null : $request->status;
        $phase = !is_numeric($request->phase) ? 1 : $request->phase;
        $inspections = ($request->role == User::ROLES["plumber"] ? $this->getPlumberInspections($limit, $status, $phase) : $this->getInspectorInspections($limit, $status, $phase));
        return ResponseHelper::success($inspections, true);
    }

    /**
     * @param      $limit
     * @param null $status
     * @param null $phase
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getPlumberInspections($limit, $status = null, $phase = null)
    {
        $inspections = DB::table("inspections")
            ->selectRaw("inspections.id, '" . $this->base_url . "' || '/uploads/' || inspection_images.image as image, 'project_name' as project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as inspector, CASE WHEN users.full_name IS NULL THEN 0 ELSE 1 END as hasInspector")
            ->leftJoin("phases", "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id")
            ->leftJoin("users", "users.id", "=", "inspection_inspectors.inspector_id")
            ->leftJoin("inspection_images", "inspection_images.inspection_id", "=", "inspections.id")
            ->where(["inspections.plumber_id" => Auth::guard('api')->user()->id])
            ->where(["phases.phase" => $phase])
            ->groupBy("inspections.id", "phases.phase", "phases.status", "users.full_name", "address", "apartment", "inspection_images.image");
        if (null != $status) {
            $inspections->where("phases.status", $status);
        }
        return $inspections->paginate($limit);
    }

    /**
     * @param      $limit
     * @param null $status
     * @param null $phase
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getInspectorInspections($limit, $status = null, $phase = null)
    {
        $inspections = DB::table("inspections")
            ->selectRaw("inspections.id, '" . $this->base_url . "' || '/uploads/' || inspection_images.image as image, 'project_name' as project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as plumber, (SELECT (COUNT(id)) FROM phases WHERE phases.inspection_id = inspections.id AND phase = $phase AND status = " . Phase::REJECTED . " ) as repeatCount")
            ->leftJoin("phases", "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id")
            ->leftJoin("users", "users.id", "=", "inspections.plumber_id")
            ->leftJoin("inspection_images", "inspection_images.inspection_id", "=", "inspections.id")
            ->where(["inspection_inspectors.inspector_id" => Auth::guard('api')->user()->id])
            ->where(["phases.phase" => $phase])
            ->groupBy("inspections.id", "phases.phase", "phases.status", "users.full_name", "address", "apartment", "inspection_images.image");
        if (null != $status) {
            $inspections->where("phases.status", $status);
        }
        return $inspections->paginate($limit);
    }

    public function getInspectionDetails(Request $request)
    {
        $inspection = Inspection::with([
            'images' => function ($query) {
                $query->selectRaw("id, inspection_id, '" . $this->base_url . "' || '/uploads/' || image as image ");
            },
            'phases' => function ($query) {
                $query->selectRaw("id, inspection_id, phase, status, created_at");
            },
        ])
            ->where('id', $request->inspection)
            ->selectRaw('id, address, latitude, longitude, apartment, building_type, floor')
            ->first();

        $data['inspection'] = $inspection;
        return ResponseHelper::success($data);
    }

}
