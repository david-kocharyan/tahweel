<?php

namespace App\Http\Controllers\Api;

use App\helpers\Firebase;
use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Inspection;
use App\Model\InspectionForm;
use App\Model\InspectionInspector;
use App\Model\Phase;
use App\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'address' => 'required|max:191',
                'latitude' => 'required|max:191',
                'longitude' => 'required|max:191',
                'floor' => 'max:191',
                'apartment' => 'max:191',
                'building_type' => 'required|max:1|integer',
                'project' => 'required',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        DB::beginTransaction();
        $inspection = new Inspection();
        $inspection->address = $data["address"];
        $inspection->latitude = $data["latitude"];
        $inspection->longitude = $data["longitude"];
        $inspection->floor = $data["floor"];
        $inspection->apartment = $data["apartment"];
        $inspection->building_type = $data["building_type"];
        $inspection->project = $data["project"];
        $inspection->plumber_id = Auth::guard('api')->user()->id;
        $inspection->save();

        $phase = new Phase(["phase" => 1, "status" => Phase::NEW]);
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
        $status = $this->getStatus($request);
        $phase = !is_numeric($request->phase) ? null : $request->phase;
        $inspections = (Auth::guard('api')->user()->role == User::ROLES["plumber"] ? $this->getPlumberInspections($limit, $status, $phase) : $this->getInspectorInspections($limit, $status, $phase));
        return ResponseHelper::success($inspections, true);
    }

    public function getTotals()
    {
        $total_phase1 = $this->getTotalPhases(1);
        $total_phase2 = $this->getTotalPhases(2);
        $resp = array(
            "total_phase1" => $total_phase1,
            "total_phase2" => $total_phase2
        );
        return ResponseHelper::success($resp);
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
            ->distinct("inspections.id")
            ->selectRaw("inspections.id, project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as inspector, CASE WHEN users.full_name IS NULL THEN 0 ELSE 1 END as hasInspector")
            ->leftJoin(DB::raw(" (SELECT distinct on (inspection_id) id, status, phase, inspection_id FROM PHASES order by inspection_id, id desc) phases"), "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", function ($join){
                $join->on("inspection_inspectors.inspection_id", "=", "inspections.id")->on("inspection_inspectors.id", "=", DB::raw(" ( SELECT max(inspection_inspectors.id) FROM inspection_inspectors WHERE inspection_inspectors.inspection_id = inspections.id ) "));
            })
            ->leftJoin("users", "users.id", "=", "inspection_inspectors.inspector_id")
            ->where(["inspections.plumber_id" => Auth::guard('api')->user()->id]);
        if(null != $phase){
            $inspections->where(["phases.phase" => $phase]);
        }
        if (null != $status) {
            $inspections->whereIn("phases.status", $status);
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
            ->distinct("inspections.id")
            ->selectRaw("inspections.id, project, address, apartment, phases.phase as phase, phases.status as status, users.full_name as plumber, (SELECT (COUNT(id)) FROM phases WHERE phases.inspection_id = inspections.id AND phase = '".($phase ?? 1)."' AND status = " . Phase::REJECTED . " ) as repeatCount")
            ->leftJoin(DB::raw(" (SELECT distinct on (inspection_id) id, status, phase, inspection_id FROM PHASES order by inspection_id, id desc) phases"), "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", function ($join){
                $join->on("inspection_inspectors.inspection_id", "=", "inspections.id")->on("inspection_inspectors.id", "=", DB::raw(" ( SELECT max(inspection_inspectors.id) FROM inspection_inspectors WHERE inspection_inspectors.inspection_id = inspections.id ) "));
            })
            ->leftJoin("users", "users.id", "=", "inspections.plumber_id")
            ->where(["inspection_inspectors.inspector_id" => Auth::guard('api')->user()->id]);

         if(null != $phase){
             $inspections->where(["phases.phase" => $phase]);
         }
        if (null != $status) {
            $inspections->whereIn("phases.status", $status);
        }
        return $inspections->paginate($limit);
    }

    public function getInspectionDetails($inspection_id)
    {
        $role = Auth::guard('api')->user()->role;
        $inspection = Inspection::with([
            'issue_phase1' => function ($query) {
                $query->where("phase", 1);
                $query->leftJoin("users", "users.id", "=", "inspection_forms.inspector_id");
                $query->selectRaw("inspection_forms.id, inspection_id, users.full_name as inspector, inspection_forms.phase, inspection_forms.approved, reason, (extract(EPOCH from inspection_forms.created_at) * 1000) as date");
            },
            'issue_phase2' => function ($query) {
                $query->where("phase", 2);
                $query->leftJoin("users", "users.id", "=", "inspection_forms.inspector_id");
                $query->selectRaw("inspection_forms.id, inspection_id, users.full_name as inspector, inspection_forms.phase, inspection_forms.approved, reason, (extract(EPOCH from inspection_forms.created_at) * 1000) as date");
            },
        ])
            ->where('inspections.id', $inspection_id);

        $inspection->with(['latestRequestDate' => function ($query) {
            $query->selectRaw("id, inspection_id, status, (extract(EPOCH from created_at) * 1000) as date");
        }]);

        if($role == User::ROLES["plumber"]){
            $inspection->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id");
            $inspection->leftJoin("users", "users.id", "=", "inspection_inspectors.inspector_id");
        } else {
            $inspection->leftJoin("users", "users.id", "=", "inspections.plumber_id");
        }
        $inspection->leftJoin(DB::raw(" (SELECT distinct on (inspection_id) id, status, phase, inspection_id FROM PHASES order by inspection_id, id desc) phases"), "phases.inspection_id", "=", "inspections.id");
        $inspection->selectRaw("inspections.id, address, latitude, longitude, apartment, building_type, floor, project, phases.phase as phase, phases.status as status, users.full_name as name, (extract(EPOCH from inspections.created_at) * 1000) as date");

        $data['inspection'] = $inspection->first();
        return ResponseHelper::success($data);
    }

    public function plumberInspectionRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'inspection' => 'required|integer',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        $user = Auth::guard('api')->user();
        $phase = Phase::selectRaw("phases.status as status, phases.phase as phase")
            ->where(["phases.inspection_id" => $request->inspection, "inspections.plumber_id" => $user->id])
            ->join("inspections", "inspections.id", "=", "phases.inspection_id")
            ->orderBy("phases.id", "DESC")
            ->first();
        if(null != $phase) {
            $p = new Phase();
            $p->inspection_id = $request->inspection;
            $p->phase = ($phase->status == Phase::APPROVED) ? 2 : $phase->phase;
            $p->status = ($phase->status == Phase::APPROVED) ? Phase::NEW : Phase::REPEATED;
            $p->save();

            $inspection = InspectionInspector::where("inspection_id", $request->inspection)->orderBy("id", "DESC")->first();
            $inspector = User::find($inspection->inspector_id);
            $tokens = $inspector->tokens()->get()->pluck('token')->toArray();
            Firebase::send($tokens, "Dear $user->full_name, You Have a New Inspection Request  (Phase: $p->phase) ");

            return ResponseHelper::success(array());
        }
        return ResponseHelper::fail("The requested inspection cannot be edited!", 403);
    }

    private function getTotalPhases($phase)
    {
        $inspections = DB::table("inspections")
            ->selectRaw("distinct on (inspections.id) inspections.id")
            ->leftJoin(DB::raw(" (SELECT distinct on (inspection_id) id, status, phase, inspection_id FROM PHASES order by inspection_id, id desc) phases"), "phases.inspection_id", "=", "inspections.id")
            ->leftJoin("inspection_inspectors", "inspection_inspectors.inspection_id", "=", "inspections.id")
            ->leftJoin("users", "users.id", "=", "inspections.plumber_id");

        if(Auth::guard('api')->user()->role == 1) {
            $inspections->where(["inspections.plumber_id" => Auth::guard('api')->user()->id]);
        } else {
            $inspections->where(["inspection_inspectors.inspector_id" => Auth::guard('api')->user()->id]);
        }

        $inspections->where(["phases.phase" => $phase])->where("phases.status", "!=", Phase::COMPLETED);
        return $inspections->get()->count();
    }

    private function getStatus($request)
    {
        $count = count(explode('&', $_SERVER['QUERY_STRING']));
        $statuses = null;
        if($count > 0) {
            for ($i = 1; $i <= $count; $i++) {
                if(null != $request->{'status'.$i} && is_numeric($request->{'status'.$i})){
                    if(!is_array($statuses))  $statuses = [];
                    $statuses[] = $request->{'status'.$i};
                } else {
                   break;
                }
            }
        }
        return $statuses;
    }

}
