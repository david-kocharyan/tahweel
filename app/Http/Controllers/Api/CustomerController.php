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
                'email' => 'max:191|email|unique:customers',
                'phone' => 'max:191',
                'shop' => 'max:191',
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
}
