<?php

namespace App\Http\Controllers\Api;

use App\helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Model\InspectionForm;
use App\Model\InspectionInspector;
use App\Model\Phase;
use Illuminate\Http\Request;
use App\helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class InspectionFormController extends Controller
{

    private $base_url;

    public function __construct()
    {
        $this->base_url = URL::to('/');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'inspection_id' => 'required|integer',
                'pre_plaster' => 'required|integer',
                'before_tiles_installer' => 'required|integer',
                'final_after_finishing' => 'required|integer',
                'bathrooms_inspected' => 'required|integer',
                'kitchen_inspected' => 'required|integer',
                'service_counters_inspected' => 'required|integer',
                'bathroom_other_tahweel_materials' => 'required|integer',
                'bathroom_other_technical_issue' => 'max:3000',
                'roof_other_tahweel_materials' => 'required|integer',
                'roof_other_tahweel_valve' => 'required|integer',
                'roof_other_technical_issue' => 'max:3000',
                'manifold_other_tahweel_materials' => 'required|integer',
                'manifold_other_tahweel_valve' => 'required|integer',
                'manifold_sunlight' => 'required|integer',
                'manifold_insulated' => 'required|integer',
                'signature' => 'required|image',
                'approved' => 'required|min:0|max:1',
                'reason' => 'max:3000',
                'warranty' => 'required|integer',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = Auth::guard('api')->user();

        $insp = InspectionInspector::where(["inspection_id" => $request->inspection_id, "inspector_id" => $user->id])->first();
        if(null == $insp) {
            return ResponseHelper::fail("You cannot update this resource", 403);
        }

        $image = FileUploadHelper::upload($request->signature, ['*'], "");
        DB::beginTransaction();

        $form = new InspectionForm();
        $form->inspection_id = $request->inspection_id;
        $form->inspector_id = Auth::guard('api')->user()->id;
        $form->pre_plaster = $request->pre_plaster;
        $form->before_tiles_installer = $request->before_tiles_installer ?? 0;
        $form->final_after_finishing = $request->final_after_finishing ?? 0;
        $form->bathrooms_inspected = $request->bathrooms_inspected ?? 0;
        $form->kitchen_inspected = $request->kitchen_inspected ?? 0;
        $form->service_counters_inspected = $request->service_counters_inspected ?? 0;
        $form->bathroom_other_tahweel_materials = $request->bathroom_other_tahweel_materials ?? 0;
        $form->bathroom_other_tahweel_valve = $request->bathroom_other_tahweel_valve ?? 0;
        $form->bathroom_other_technical_issue = $request->bathroom_other_technical_issue;
        $form->roof_other_tahweel_materials = $request->roof_other_tahweel_materials ?? 0;
        $form->roof_other_tahweel_valve = $request->roof_other_tahweel_valve ?? 0;
        $form->roof_other_technical_issue = $request->roof_other_technical_issue;
        $form->manifold_other_tahweel_materials = $request->manifold_other_tahweel_materials ?? 0;
        $form->manifold_other_tahweel_valve = $request->manifold_other_tahweel_valve ?? 0;
        $form->manifold_sunlight = $request->manifold_sunlight ?? 0;
        $form->manifold_insulated = $request->manifold_insulated ?? 0;
        $form->signature = $image;
        $form->approved = $request->approved ?? 0;
        $form->reason = $request->reason;
        $form->warranty = $request->warranty ?? 0;

        $form->save();

        $currentPhase = Phase::where("inspection_id", $request->inspection_id)->orderBy("id", "DESC")->first();
        $phase = new Phase();
        $phase->inspection_id = $request->inspection_id;
        $phase->phase = ($request->approved == InspectionForm::DECLINED) ? ($currentPhase->phase ?? 1) : ( ($request->warranty == InspectionForm::NO_WARRANTY) ? 2 : $currentPhase->phase );
        $phase->status = ($request->approved == InspectionForm::DECLINED) ? (Phase::REJECTED) : ( ($request->warranty == InspectionForm::NO_WARRANTY) ? Phase::NEW : Phase::COMPLETED );
        $phase->save();
        DB::commit();
        return ResponseHelper::success(array());
    }

    public function getForm(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'inspection' => 'required|integer',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = Auth::guard('api')->user();
        $insp = InspectionInspector::where(["inspection_id" => $request->inspection_id, "inspector_id" => $user->id])->first();
        if(null == $insp) {
            return ResponseHelper::fail("You cannot update this resource", 403);
        }

        $form = InspectionForm::where("inspection_id", $request->inspection)
            ->selectRaw("inspection_id, pre_plaster, before_tiles_installer, final_after_finishing, bathrooms_inspected, kitchen_inspected, service_counters_inspected, bathroom_other_tahweel_materials, bathroom_other_tahweel_valve, bathroom_other_technical_issue, roof_other_tahweel_materials, roof_other_tahweel_valve, roof_other_technical_issue, manifold_other_tahweel_materials, manifold_other_tahweel_valve, manifold_sunlight, manifold_insulated, '".$this->base_url."' || '/uploads/' || signature as signature_full, signature as signature_string, approved, reason, warranty")->orderBy("id", "DESC")->first();
        $resp = array("form" => $form);
        return ResponseHelper::success($resp);
    }
}
