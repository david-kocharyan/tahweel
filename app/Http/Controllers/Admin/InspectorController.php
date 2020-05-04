<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Firebase;
use App\Http\Controllers\Controller;
use App\Mail\InspectorMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class InspectorController extends Controller
{

    const FOLDER = "admin.inspector";
    const TITLE = "Inspectors";
    const ROUTE = "/admin/inspectors";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('role', User::ROLES['inspector'])->get();
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
        $title = "Create " . self::TITLE;
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
            'full_name' => 'required|max:200',
            'email' => 'required|email|unique:users,email',
            "password" => "required|min:6"
        ]);

        $inspector = new User;
        $inspector->full_name = $request->full_name;
        $inspector->email = $request->email;
        $inspector->approved = 1;
        $inspector->role = User::ROLES['inspector'];
        $inspector->password = Hash::make($request->password);
        $inspector->save();

        if ($inspector->id) {
            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            Mail::to($request->email)->send(new InspectorMail($details));
        }

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|max:200',
            "email" => "required|unique:users,email," . $id,
        ]);

        $inspector = User::with("tokens")->find($id);
        $sendNotif = false;
        if(!$inspector->approved && $request->approved) {
            $sendNotif = true;
        }
        $inspector->full_name = $request->full_name;
        $inspector->email = $request->email;
        $inspector->approved = $request->approved ?? 0;
        if ($request->password) $inspector->password = Hash::make($request->password);
        $inspector->save();

        if ($request->password) {
            $details = [
                'title' => 'Your password in Tahweel Application',
                'body' => "Hello dear $request->full_name. Your password is` $request->password",
            ];
            Mail::to($request->email)->send(new InspectorMail($details));
        }

        if($sendNotif) {
            $tokens = $inspector->tokens()->get()->pluck('token')->toArray();
            Firebase::send($tokens, "Notif");
        }

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect(self::ROUTE);
    }
}
