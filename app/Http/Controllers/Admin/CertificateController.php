<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    const FOLDER = "admin.certificate";
    const TITLE = "Certificate";
    const ROUTE = "/admin/certificates";

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

        $certificate = new Certificate;
        $certificate->name = $request->name;
        $certificate->name = $request->name;
        $certificate->name = $request->name;
        $certificate->save();


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
        return view(self::FOLDER . ".create", compact('title', 'route', 'type', 'data'));
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
            'file' => 'required',
            'type' => 'required|min:1|max:2',
        ]);

        $certificate->name = $request->name;
        $certificate->name = $request->name;
        $certificate->name = $request->name;
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
        Certificate::destroy($certificate->id);
    }
}
