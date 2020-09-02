<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Redeem;
use Illuminate\Http\Request;

class RedeemsController extends Controller
{
    const FOLDER = "admin.redeems";
    const TITLE = "Redeems";
    const ROUTE = "/admin/redeems";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Redeem::with(['user', "product" => function ($query) {
            $query->selectRaw("products.id, product_languages.name, product_languages.description, (extract(EPOCH from products.created_at) * 1000) as date, products.point")
                ->leftJoin('product_languages', 'products.id', '=', 'product_languages.product_id')
                ->where(array('product_languages.language_id' => 1));
        }])->get();

        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".index", compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
