<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PointCoeficient;
use Illuminate\Http\Request;

class PointCoeficientController extends Controller
{

    const FOLDER = 'admin.coefficient';
    const ROUTE = '/admin/points';
    const TITLE = 'Points Coefficient';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PointCoeficient::all();
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
     * @param  \App\Model\PointCoeficient  $pointCoeficient
     * @return \Illuminate\Http\Response
     */
    public function show(PointCoeficient $pointCoeficient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\PointCoeficient  $pointCoeficient
     * @return \Illuminate\Http\Response
     */
    public function edit(PointCoeficient $pointCoeficient, $id)
    {
        $data = PointCoeficient::find($id);
        $title = "Edit Point Coefficient";
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\PointCoeficient  $pointCoeficient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PointCoeficient $pointCoeficient, $id)
    {
        $request->validate([
            'coefficient' => 'required|numeric',
        ]);

        $pointCoeficient = PointCoeficient::find($id);
        $pointCoeficient->point = $request->coefficient;
        $pointCoeficient->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\PointCoeficient  $pointCoeficient
     * @return \Illuminate\Http\Response
     */
    public function destroy(PointCoeficient $pointCoeficient)
    {
        //
    }
}
