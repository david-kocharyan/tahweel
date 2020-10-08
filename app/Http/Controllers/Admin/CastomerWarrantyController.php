<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Warranty;
use App\Model\CastomerWarrantySave;
use App\Model\Certificate;
use App\Model\Customer;
use App\Model\InspectionForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class CastomerWarrantyController extends Controller
{

    const FOLDER = "admin.warranty";
    const TITLE = "Warranty Approve";
    const ROUTE = "/admin/warranty";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CastomerWarrantySave::with(['inspector', 'customer'])->get();
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
     * @param \App\Model\CastomerWarranty $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function show(CastomerWarranty $castomerWarranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Model\CastomerWarranty $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function edit(CastomerWarranty $castomerWarranty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request    $request
     * @param \App\Model\CastomerWarranty $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = CastomerWarrantySave::where('id', $id)->first();
        $customer = Customer::where('inspection_id', $data->inspection_id)->first();

        if ($customer->email) {
            $link = URL::to('/') . "/api/v1/inspections/warranty/$data->warranty_type/$data->inspection_id";
            $details = [
                'title' => 'Warranty',
                'body' => "Hello $customer->full_name. Please follow the link to get a warranty!",
                'link' => $link,
            ];
            Mail::to("$customer->email")->send(new Warranty($details));

            $data->status = 1;
            $data->save();
        } else {
            $data->status = 0;
            $data->save();
        }

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Model\CastomerWarranty $castomerWarranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(CastomerWarranty $castomerWarranty)
    {
        //
    }

    public function downloadCertificate($id)
    {
        $data = CastomerWarrantySave::where('id', $id)->first();
        $customer = Customer::where('inspection_id', $data->inspection_id)->first();
        $file = Certificate::where('type', $data->warranty_type)->first()->file;

        $img = Image::make(public_path("uploads/$file"));
        $img->rotate(-90);
        $img->text($customer->full_name, 1000 , 1170 , function($font) {
            $font->file(public_path('assets/css/MotionPicture_PersonalUseOnly.ttf'));
            $font->size(100);
            $font->align('center');
            $font->valign('center');
        });

        $img->save(public_path("uploads/certificates/warranty_$customer->id.jpg"));


        $file= public_path("uploads/certificates/warranty_$customer->id.jpg");

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, 'warranty.jpg', $headers);
    }

}
