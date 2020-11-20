<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    const FOLDER = 'admin.city';
    const ROUTE = '/admin/cities';
    const TITLE = 'Cities';
    const COUNTRY = 1;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = City::orderBy('id', "ASC")->get();
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
        $title = "Create City";
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
            'en' => 'required',
            'ar' => 'required',
            'ur' => 'required',
        ]);

        $city = new City();
        $city->country_id = self::COUNTRY;
        $city->en = $request->en;
        $city->ar = $request->ar;
        $city->ur = $request->ur;
        $city->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $data = $city;
        $title = "Edit City";
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'en' => 'required',
            'ar' => 'required',
            'ur' => 'required',
        ]);

        $city->country_id = self::COUNTRY;
        $city->en = $request->en;
        $city->ar = $request->ar;
        $city->ur = $request->ur;
        $city->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        City::destroy($city->id);
        return redirect(self::ROUTE);
    }
}
