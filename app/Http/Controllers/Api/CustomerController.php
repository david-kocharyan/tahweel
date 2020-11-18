<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Model\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'inspection_id' => 'required|integer',
                'full_name' => 'required|max:191',
                'email' => 'max:191|email',
                'phone' => 'required|max:191',
                'shop' => 'max:191|nullable',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $customer = new Customer();
        $customer->inspection_id = $data["inspection_id"];
        $customer->full_name = $data["full_name"];
        $customer->email = $data["email"];
        $customer->phone = $data["phone"];
        $customer->shop = $data["shop"];

        if($customer->save()){
            return ResponseHelper::success(array());
        }
        return ResponseHelper::fail("Something Went Wrong", 500);
    }

    public function getCustomer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'inspection' => 'required|integer',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $customer = Customer::where("inspection_id", $request->inspection)->selectRaw("full_name, email, phone, shop")->orderBy("id", "DESC")->first();
        if(null == $customer) {
            return ResponseHelper::fail("Customer Not Found", 422);
        }
        $resp = array(
            "customer" => $customer
        );
        return ResponseHelper::success($resp);
    }
}
