<?php

namespace App\Http\Controllers\Admin;

use App\helpers\FileUploadHelper;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

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
        $data = Product::all();
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
        return view(self::FOLDER . ".create", compact('title', 'route'));
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
            'name' => 'required|max:190',
            'point' => 'required|numeric|min:1',
            "image" => "required|image",
            "description" => "max:150",
        ]);

        $image = FileUploadHelper::upload($request->image, ["*"], self::UPLOAD);

        $product = new Product();
        $product->name = $request->name;
        $product->point = $request->point;
        $product->image = $image ?? "";
        $product->description = $request->description;
        $product->save();

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
        return view(self::FOLDER . ".create", compact('title', 'route', 'product'));
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
            'name' => 'required|max:190',
            'point' => 'required|numeric|min:1',
            "description" => "max:150",
        ]);
        if(null != $request->image) {
            $image = FileUploadHelper::upload($request->image, ["*"], self::UPLOAD);
            $product->image = $image ?? "";
        }

        $product->name = $request->name;
        $product->point = $request->point;
        $product->description = $request->description;
        $product->save();

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
