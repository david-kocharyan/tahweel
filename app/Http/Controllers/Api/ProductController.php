<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Redeem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = URL::to('/');
    }

    public function getProducts(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $products = Product::selectRaw("id, name, (extract(EPOCH from created_at) * 1000) as date, '".$this->base_url."' || '/uploads/' || image as image ")->orderBy("id", "DESC")->paginate($limit);

        return ResponseHelper::success($products, true);
    }

    public function getRedeems(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 20 : $request->limit;
        $redeems = Redeem::selectRaw("id, point, (extract(EPOCH from created_at) * 1000) as redeemDate, product_id")->where("plumber_id", Auth::guard('api')->user()->id)->orderBy("id", "DESC")->with(["product" => function($query) {
            $query->selectRaw("id, name, (extract(EPOCH from created_at) * 1000) as date, '".$this->base_url."' || '/uploads/' || image as image ");
        }])->paginate($limit);

        return ResponseHelper::success($redeems, true);
    }

    public function buyProduct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'id' => 'required|integer',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $points = AuthController::getPointsFromDb(); // Current user's points
        $product = Product::find($data["id"]);
        if(null == $product) {
            return ResponseHelper::fail("Product Not Found", 404);
        }

        if($product->point > $points) {
            return ResponseHelper::fail("You have not enough points to buy this item", 422);
        }

        $redeem = new Redeem();
        $redeem->product_id = $product->id;
        $redeem->plumber_id = Auth::guard('api')->user()->id;
        $redeem->point = $product->point;
        $redeem->save();

        return ResponseHelper::success(array());
    }
}
