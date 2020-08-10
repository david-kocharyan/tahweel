<?php

namespace App\Http\Controllers\Admin;

use App\helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Model\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Kreait\Firebase\Storage;

class CertificateController extends Controller
{
    const FOLDER = "admin.certificate";
    const TITLE = "Certificate";
    const ROUTE = "/admin/certificates";
    const UPLOAD = "certificates";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Certificate::all();
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
        $type = Certificate::TYPE;
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".create", compact('title', 'route', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required',
            'type' => 'required|min:1|max:2',
        ]);

        $file = FileUploadHelper::upload($request->file, ["*"], self::UPLOAD);
        DB::beginTransaction();

        $certificate = new Certificate;
        $certificate->name = $request->name;
        $certificate->file = $file ?? "";
        $certificate->type = $request->type;
        $certificate->save();

        DB::commit();
        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     * @param \App\Model\Certificate $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Model\Certificate $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        $type = Certificate::TYPE;
        $data = $certificate;
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'type', 'data'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Certificate   $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|min:1|max:2',
        ]);


        if(null != $request->image) {
            $file = FileUploadHelper::upload($request->file, ["*"], self::UPLOAD);
            $certificate->image = $file ?? "";
        }
        $certificate->name = $request->name;
        $certificate->type = $request->type;
        $certificate->save();

        return redirect(self::ROUTE);

    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Model\Certificate $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        if($certificate->delete()){
            if(File::exists(asset("uploads/$certificate->file"))) {
                File::delete(asset("uploads/$certificate->file"));
            }
        }

        return redirect(self::ROUTE);
    }
}
