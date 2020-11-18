<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Inspection;
use App\Model\InspectionInspector;
use App\Model\Redeem;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    const View = 'admin';
    const Route = '/';
    const Title = 'Admin Dashboard';

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = self::Title;
        return view(self::View . ".index", compact('title'));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate()
    {
        $plumber = User::where(array('role' => 1, 'approved' => 0))->count();
        $inspector = User::where(array('role' => 2, 'approved' => 0))->count();
        $redeems = Redeem::where('status', 0)->count();

        $inspections_all = Inspection::count();
        $inspections_with_inspector = InspectionInspector::count();
        $inspections = $inspections_all - $inspections_with_inspector;

        $data = array(
            'plumber' => $plumber,
            'inspector' => $inspector,
            'redeems' => $redeems,
            'inspections' => $inspections,
        );
        return response()->json($data, 200);
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
     * @param \App\Model\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Model\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Admin         $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Model\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
