<?php

namespace App\Http\Controllers\Admin;

use App\helpers\FileUploadHelper;
use App\Model\Language;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    const FOLDER = "admin.products";
    const TITLE = "Products";
    const ROUTE = "/admin/products";
    const UPLOAD = "products";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::with("languages")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".index", compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $languages = Language::all();
        return view(self::FOLDER . ".create", compact('title', 'route', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data.*.name' => 'required|max:100',
            "data.*.description" => "max:150",
            'point' => 'required|numeric|min:1|max:1000000',
            "image" => "required|image",
        ]);

        $image = FileUploadHelper::upload($request->image, ["*"], self::UPLOAD);
        DB::beginTransaction();

        $product = new Product();
        $product->point = $request->point;
        $product->image = $image ?? "";
        $product->save();

        $product->languages()->sync($request->data);
        DB::commit();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $languages = Language::all();
        return view(self::FOLDER . ".create", compact('title', 'route', 'product', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'data.*.name' => 'required|max:100',
            "data.*.description" => "max:150",
            'point' => 'required|numeric|min:1|max:1000000',
        ]);
        DB::beginTransaction();
        if(null != $request->image) {
            $image = FileUploadHelper::upload($request->image, ["*"], self::UPLOAD);
            $product->image = $image ?? "";
        }
        $pushData = [];
        foreach ($request->data as $d) {
            $pushData[$d["language_id"]] = $d;
        }
        $product->point = $request->point;
        $product->save();
        $product->languages()->sync($pushData);

        DB::commit();
        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete()){
            if(File::exists(asset("uploads/$product->image"))) {
                File::delete(asset("uploads/$product->image"));
            }
        }
        return redirect(self::ROUTE);
    }
}
