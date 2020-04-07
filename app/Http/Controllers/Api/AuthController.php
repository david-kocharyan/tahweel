<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\helpers\ResponseHelper;

class AuthController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'full_name' => 'required|max:100',
                'email' => 'required|unique:users|max:150',
                'role' => 'required|integer|min:1|max:2',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = new User;
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->createToken('Personal Access Token')->accessToken;
        $tokens = $this->get_token($request->email, $request->password);

        $resp = array(
            "user" => $user,
            "tokens" => $tokens
        );

        return ResponseHelper::success($resp);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|max:150',
                'password' => 'required',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = User::where(['email' => $request->email, "approved" => 1])->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $user->createToken('Personal Access Token')->accessToken;
                $tokens = $this->get_token($request->email, $request->password);

                $resp = array(
                    "user" => $user,
                    "tokens" => $tokens
                );

                return ResponseHelper::success($resp);
            }
        }
        return ResponseHelper::fail("Wrong Credentials", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function refreshToken(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        try{
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => env('PASS_GRAND_TOKEN_ID'),
                    'client_secret' => env('PASS_GRAND_TOKEN_SECRET'),
                ],
            ]);
            return ResponseHelper::success(json_decode((string)$response->getBody(), true));
        } catch (\Exception $exception){
            return ResponseHelper::fail("Something Went Wrong", 422);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();

        return ResponseHelper::success(array());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        $user = Auth::guard('api')->user();
        return ResponseHelper::success($user);
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    private function get_token($username, $password)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('PASS_GRAND_TOKEN_ID'),
                'client_secret' => env('PASS_GRAND_TOKEN_SECRET'),
                'username' => $username,
                'password' => $password,
                'scope' => '',
            ],
        ]);
        return json_decode((string)$response->getBody(), true);
    }

}
