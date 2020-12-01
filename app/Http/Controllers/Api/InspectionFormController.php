<?php

namespace App\Http\Controllers\Api;

use App\helpers\FileUploadHelper;
use App\helpers\Firebase;
use App\Http\Controllers\Controller;
use App\Mail\Warranty;
use App\Model\CastomerWarranty;
use App\Model\CastomerWarrantySave;
use App\Model\Certificate;
use App\Model\Customer;
use App\Model\Inspection;
use App\Model\InspectionForm;
use App\Model\InspectionInspector;
use App\Model\Notification;
use App\Model\Phase;
use App\Model\PlumberPoint;
use App\Model\PointCoeficient;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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

        $currentPhase = Phase::where("inspection_id", $request->inspection_id)->orderBy("id", "DESC")->first();

        $image = FileUploadHelper::upload($request->signature, ['*'], "signatures");
        DB::beginTransaction();

        $form = new InspectionForm();
        $form->inspection_id = $request->inspection_id;
        $form->inspector_id = Auth::guard('api')->user()->id;
        $form->phase = $currentPhase->phase;
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

        if ($request->warranty != 0) {
            $this->sendWarranty($request->warranty, $request->inspection_id, $form->id);
        }

        $phase = new Phase();
        $phase->inspection_id = $request->inspection_id;
        $phase->phase = ($request->approved == InspectionForm::DECLINED) ? ($currentPhase->phase ?? 1) : (($request->warranty == InspectionForm::NO_WARRANTY) ? 1 : $currentPhase->phase);
        $phase->status = ($request->approved == InspectionForm::DECLINED) ? (Phase::REJECTED) : (($request->warranty == InspectionForm::NO_WARRANTY) ? Phase::APPROVED : Phase::COMPLETED);
        $phase->save();

        // Give Points
        if ($phase->status == Phase::COMPLETED) {

            $bathroom = PointCoeficient::where('code', 'BA')->first()->point;
            $kitchen = PointCoeficient::where('code', 'KI')->first()->point;
            $service = PointCoeficient::where('code', 'SC')->first()->point;

            $plumber_point = ($form->bathrooms_inspected * $bathroom) + ($form->kitchen_inspected * $kitchen) + ($form->service_counters_inspected * $service);

            $plumberPoint = new PlumberPoint();
            $plumberPoint->inspection_id = $request->inspection_id;
            $plumberPoint->point = $plumber_point;
            $plumberPoint->save();
        }

        DB::commit();

        $inspection = Inspection::find($request->inspection_id);
        $plumber = User::find($inspection->plumber_id);
        $tokens = $plumber->tokens()->get()->pluck('token')->toArray();
        Firebase::send($tokens, "Dear $plumber->full_name, Your Request Has Been Inspected", "", "", "", Notification::INSPECTION_TYPE);

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

        $form = InspectionForm::where("inspection_id", $request->inspection)
            ->selectRaw("inspection_id, pre_plaster, before_tiles_installer, final_after_finishing, bathrooms_inspected, kitchen_inspected, service_counters_inspected, bathroom_other_tahweel_materials, bathroom_other_tahweel_valve, bathroom_other_technical_issue, roof_other_tahweel_materials, roof_other_tahweel_valve, roof_other_technical_issue, manifold_other_tahweel_materials, manifold_other_tahweel_valve, manifold_sunlight, manifold_insulated, '" . $this->base_url . "' || '/uploads/' || signature as signature_full, signature as signature_string, approved, reason, warranty, customer_approved")->orderBy("id", "DESC")->first();
        $resp = array("form" => $form);
        return ResponseHelper::success($resp);
    }

    private function sendWarranty($warranty, $inspection_id, $form_id)
    {
        $customer = Customer::where('inspection_id', $inspection_id)->first();
        $inspection = InspectionForm::where('id', $form_id)->first();

        $warranty_save = new CastomerWarrantySave;
        $warranty_save->inspection_id = $inspection_id;
        $warranty_save->inspector_id = $inspection->inspector_id;
        $warranty_save->customer_id = $customer->id;
        $warranty_save->warranty_type = $warranty;
        $warranty_save->phase = $inspection->phase;
        $warranty_save->save();
    }

    public function downloadWarranty($warranty, $inspection_id)
    {
        $form = InspectionForm::where('inspection_id', $inspection_id)->first();
        $form->customer_approved = 1;
        $form->save();

        $customer = Customer::where('inspection_id', $inspection_id)->first();
        $inspection = Inspection::find($inspection_id);
        $phase_one = Phase::where('inspection_id', $inspection_id)->where('phase', 1)->where('status', 2)->first();
        $phase_two = Phase::where('inspection_id', $inspection_id)->where('phase', 2)->where('status', 2)->first();


        return view('certificate.certificate_' . $warranty,compact('customer', 'inspection', 'phase_one', 'phase_two'));
    }
}
