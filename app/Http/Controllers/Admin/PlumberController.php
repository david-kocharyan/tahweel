<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\helpers\QrGenerator;
use App\Http\Controllers\Controller;
use App\Mail\PlumberMail;
use App\Model\FcmToken;
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
        $data = User::where('role', User::ROLES['plumber'])->get();
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
        $title = "Create " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".create", compact('title', 'route'));
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
            'email' => 'required|email|unique:users,email',
            "password" => "required|min:6"
        ]);
        $plumber = new User;
        $plumber->full_name = $request->full_name;
        $plumber->email = $request->email;
        $plumber->approved = 1;
        $plumber->role = User::ROLES['plumber'];
        $plumber->password = Hash::make($request->password);
        $plumber->save();

        if ($plumber->id) {
            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            try{
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
        $data = User::find($id);
        $title = "Edit " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact('title', 'route', 'data'));
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
           "email" => "required|unique:users,email," . $id,
       ]);
        $plumber = User::with("tokens")->find($id);
        $sendNotif = false;
        if(!$plumber->approved && $request->approved) {
            $sendNotif = true;
        }
        $plumber->full_name = $request->full_name;
        $plumber->email = $request->email;
        $plumber->approved = $request->approved ?? 0;
        if ($request->password) $plumber->password = Hash::make($request->password);
        $plumber->save();

        if ($request->password) {
            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            try{
                Mail::to($request->email)->send(new PlumberMail($details));
            } catch (\Exception $exception) {
                dd($exception);
            }
        }

        if($sendNotif) {
            $tokens = $plumber->tokens()->get()->pluck('token')->toArray();
            dd($tokens);
            Firebase::send($tokens, "Dear $plumber->full_name, Your Account Has Been Approved");
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
}
