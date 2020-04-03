<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    const OK = 200;
    const UNAUTHORIZED = 401;
    const Unprocessable_Entity_Explained = 422;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'role' => 'required',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $bin => $key) {
                if ($bin == 0) {
                    $message = $key;
                } else {
                    break;
                }
            }
            return response()->json(
                $data = array(
                    'data' => array(),
                    'success' => false,
                    'msg' => $message[0],
                ), self::UNAUTHORIZED);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->createToken('Personal Access Token')->accessToken;
        $tokens = $this->get_token($request->email, $request->password);

        return response()->json(
            $data = array(
                'data' => array(
                    'user' => $user,
                    'tokens' => $tokens,
                ),
                'success' => true,
                'msg' => 'Registered Successfully',
            ), self::OK);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $bin => $key) {
                if ($bin == 0) {
                    $message = $key;
                } else {
                    break;
                }
            }
            return response()->json(
                $data = array(
                    'data' => array(),
                    'success' => false,
                    'msg' => $message[0],
                ), self::UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $user->createToken('AppName')->accessToken;
                $tokens = $this->get_token($request->email, $request->password);

                return response()->json(
                    $data = array(
                        'data' => array(
                            'user' => $user,
                            'tokens' => $tokens,
                        ),
                        'success' => true,
                        'msg' => 'Logged Successfully',
                    ), self::OK);
            } else {
                return response()->json(
                    $data = array(
                        'data' => array(),
                        'success' => false,
                        'msg' => 'Invalid Password',
                    ), self::Unprocessable_Entity_Explained);
            }
        } else {
            return response()->json(
                $data = array(
                    'data' => array(),
                    'success' => false,
                    'msg' => 'Unauthenticated',
                ), self::Unprocessable_Entity_Explained);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function refreshToken(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => env('PASS_GRAND_TOKEN_ID'),
                'client_secret' => env('PASS_GRAND_TOKEN_SECRET'),
            ],
        ]);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();

        return response()->json($data = array(
            'data' => array(),
            'success' => true,
            'msg' => 'You have been successfully logged out!',
        ), self::OK);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        $user = Auth::guard('api')->user();
        return response()->json($data = array(
            'data' => array(
                "user" => $user
            ),
            'success' => true,
            'msg' => '',
        ), self::OK);
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
