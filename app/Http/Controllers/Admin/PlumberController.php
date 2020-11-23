<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\helpers\QrGenerator;
use App\Http\Controllers\Controller;
use App\Mail\PlumberMail;
use App\Model\City;
use App\Model\FcmToken;
use App\Model\Phone;
use App\Model\PlumberPointsFromAdmin;
use App\Model\Redeem;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PlumberController extends Controller
{
    const FOLDER = "admin.plumber";
    const TITLE = "Plumbers";
    const ROUTE = "/admin/plumbers";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with(['phone','city'])->where('role', User::ROLES['plumber'])->get();
        foreach ($data as $key =>$val)
        {
            $pointsEarnedFromAdmin = PlumberPointsFromAdmin::where("plumber_id", $val->id)->sum("points") ?? 0;
            $pointsEarned = User::where("id", $val->id)->with("pointsEarned")->first()->pointsEarned->sum("point") ?? 0;
            $pointsRedeemed = Redeem::where("plumber_id", $val->id)->sum("point") ?? 0;
            $val->point = (($pointsEarnedFromAdmin + $pointsEarned) - $pointsRedeemed);
        }

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
        $city = City::all();
        $title = "Create " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".create", compact('title', 'route', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|max:200',
            'username' => 'required|max:200|unique:users,username',
            'city' => 'required',
            'email' => 'nullable|email',
            "password" => "required|min:6",
            "phone" => "required|min:6"
        ]);
        $plumber = new User;
        $plumber->full_name = $request->full_name;
        $plumber->city_id = $request->city;
        $plumber->username = $request->username;
        $plumber->email = $request->email;
        $plumber->approved = 1;
        $plumber->role = User::ROLES['plumber'];
        $plumber->password = Hash::make($request->password);
        $plumber->save();

        if ($plumber->id) {

            $phone = new Phone;
            $phone->user_id = $plumber->id;
            $phone->verification = 1;
            $phone->phone = $request->phone;
            $phone->save();

            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            try {
                Mail::to($request->email)->send(new PlumberMail($details));
            } catch (\Exception $e) {

            }
        }

        return redirect(self::ROUTE);
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
        $data = User::with('phone')->where('id',$id)->first();
        $city = City::all();
        $title = "Edit " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'data', 'city'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|max:200',
            'username' => 'required|max:200|unique:users,username,' . $id,
            'city' => 'required',
            'phone' => 'required',
        ]);

        $plumber = User::with("tokens")->find($id);
        $sendNotif = false;
        if (!$plumber->approved && $request->approved) {
            $sendNotif = true;
        }

        $plumber->city_id = $request->city;
        $plumber->username = $request->username;
        $plumber->full_name = $request->full_name;
        $plumber->email = $request->email;
        $plumber->approved = $request->approved ?? 0;
        if ($request->password) $plumber->password = Hash::make($request->password);
        $plumber->save();

        $phone = Phone::where('user_id', $plumber->id)->first();
        $phone->phone = $request->phone;
        $phone->save();

        if ($request->password) {
            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            try {
                Mail::to($request->email)->send(new PlumberMail($details));
            } catch (\Exception $exception) {
                dd($exception);
            }
        }

        if ($sendNotif) {
            $tokens = $plumber->tokens()->get()->pluck('token')->toArray();
            Firebase::send($tokens, "Dear $plumber->full_name, Your Account Has Been Approved", null, null, null, null, null, null, 1);
        }

        return redirect(self::ROUTE);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect(self::ROUTE);
    }



    public function addPoint(Request $request)
    {
        $request->validate([
            'point' => 'required|numeric',
            'plumber_id' => 'required|numeric',
        ]);

        $plumber_point = new PlumberPointsFromAdmin;
        $plumber_point->plumber_id = $request->plumber_id;
        $plumber_point->points = $request->point;
        $plumber_point->save();


        return redirect(self::ROUTE);
    }


}
