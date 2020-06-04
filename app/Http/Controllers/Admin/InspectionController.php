<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\Http\Controllers\Controller;
use App\Model\InspectionInspector;
use App\Model\Notification;
use App\User;
use Illuminate\Http\Request;
use App\Model\Inspection;

class InspectionController extends Controller
{
    const FOLDER = "admin.inspection";
    const TITLE = "Inspections";
    const ROUTE = "/admin/inspections";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Inspection::with(['plumber', 'inspector'])->get();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Inspection::with(['plumber', 'inspector'])->where('id', $id)->first();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".show", compact('title', 'route', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspectors = User::where('role', User::ROLES['inspector'])->get();
        $chosenInspectorId = Inspection::with("inspector")->find($id)->inspector->id ?? 0;
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'inspectors', 'id', "chosenInspectorId"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'inspector' => "required",
        ]);

        $inspection_inspector = new InspectionInspector;
        $inspection_inspector->inspection_id = $id;
        $inspection_inspector->inspector_id = $request->inspector;
        $inspection_inspector->save();
        $user = User::find($request->inspector);
        $tokens = $user->tokens()->get()->pluck('token')->toArray();
        Firebase::send($tokens, "Dear $user->full_name, You Have a New Inspection Request", null, null, null, Notification::INSPECTION_TYPE);
        return  redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
