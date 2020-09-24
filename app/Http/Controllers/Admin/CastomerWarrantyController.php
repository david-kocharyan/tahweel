<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\CastomerWarrantySave;
use App\Model\InspectionForm;
use Illuminate\Http\Request;

class CastomerWarrantyController extends Controller
{

    const FOLDER = "admin.warranty";
    const TITLE = "Warranty Approve";
    const ROUTE = "/admin/warranty";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CastomerWarrantySave::with(['inspector', 'customer'])->get();
        dd($data);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\CastomerWarranty  $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function show(CastomerWarranty $castomerWarranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\CastomerWarranty  $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function edit(CastomerWarranty $castomerWarranty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\CastomerWarranty  $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CastomerWarranty $castomerWarranty)
    {

//        $link = $this->base_url . "/api/v1/inspections/warranty/$warranty/$inspection_id";
//        $details = [
//            'title' => 'Warranty',
//            'body' => "Hello $customer->full_name. Please follow the link to get a warranty!",
//            'link' => $link,
//        ];
//        Mail::to("$customer->email")->send(new Warranty($details));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\CastomerWarranty  $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(CastomerWarranty $castomerWarranty)
    {
        //
    }
}
