<?php

namespace App\Http\Controllers\Api;

use App\helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Model\InspectionForm;
use Illuminate\Http\Request;
use App\helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InspectionFormController extends Controller
{
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

        $image = FileUploadHelper::upload($request->signature, ['*'], "");

        $form = new InspectionForm();
        $form->inspection_id = $request->inspection_id;
        $form->inspector_id = Auth::guard('api')->user()->id;
        $form->pre_plaster = $request->pre_plaster;
        $form->before_tiles_installer = $request->before_tiles_installer;
        $form->final_after_finishing = $request->final_after_finishing;
        $form->bathrooms_inspected = $request->bathrooms_inspected;
        $form->kitchen_inspected = $request->kitchen_inspected;
        $form->service_counters_inspected = $request->service_counters_inspected;
        $form->bathroom_other_tahweel_materials = $request->bathroom_other_tahweel_materials;
        $form->bathroom_other_technical_issue = $request->bathroom_other_technical_issue;
        $form->roof_other_tahweel_materials = $request->roof_other_tahweel_materials;
        $form->roof_other_tahweel_valve = $request->roof_other_tahweel_valve;
        $form->roof_other_technical_issue = $request->roof_other_technical_issue;
        $form->manifold_other_tahweel_materials = $request->manifold_other_tahweel_materials;
        $form->manifold_other_tahweel_valve = $request->manifold_other_tahweel_valve;
        $form->manifold_sunlight = $request->manifold_sunlight;
        $form->manifold_insulated = $request->manifold_insulated;
        $form->signature = $image;
        $form->approved = $request->approved;
        $form->reason = $request->reason;
        $form->warranty = $request->warranty;

        $form->save();

        return ResponseHelper::success(array());
    }
}
