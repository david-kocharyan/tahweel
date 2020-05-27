<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
}
