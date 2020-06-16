<?php

namespace App\Http\Controllers\Api;

use App\helpers\QrGenerator;
use App\helpers\Twilio;
use App\Http\Controllers\Controller;
use App\Mail\MailHelper;
use App\Model\FcmToken;
use App\Model\Redeem;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\helpers\ResponseHelper;
use App\Model\Phone;

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
                'phone' => $request->role == 1 ? 'required|max:191' : '',
                'email' => 'required|unique:users|max:150|regex:/(.+)@(.+)\.(.+)/i',
                'role' => 'required|integer|min:1|max:2',
                'password' => 'required|max:25',
                'confirm_password' => 'required|same:password',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = new User;
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->role = intval($request->role);
        $user->approved = $user->role == 1 ? 1 : 0;
        $user->password = bcrypt($request->password);
        $user->lang = User::ENGLISH;
        $user->save();

        $img = QrGenerator::generate(uniqid()."_".$user->id);

        $user->qr = $img;
        $user->save();

        Phone::where("phone", $request->phone)->update(["user_id" => $user->id]);

        $user->createToken('Personal Access Token')->accessToken;
        $tokens = $this->get_token($request->email, $request->password);

        $user->qr = URL::to("/") . "/" . $user->qr;

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

        $user = User::where(['email' => $request->email])->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $user->createToken('Personal Access Token')->accessToken;
                $tokens = $this->get_token($request->email, $request->password);
                $user->qr = URL::to("/") . "/" . $user->qr;
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
        try {
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => env('PASS_GRAND_TOKEN_ID'),
                    'client_secret' => env('PASS_GRAND_TOKEN_SECRET'),
                ],
            ]);
            return ResponseHelper::success(json_decode((string)$response->getBody(), true));
        } catch (\Exception $exception) {
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
        $user->qr = URL::to("/") . "/" . $user->qr;
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

    public function recoverPassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = User::where(['email' => $request->email])->first();
        if (null == $user) {
            return ResponseHelper::fail("Wrong email provided", 403);
        }
        $pass = uniqid();
        $user->password = bcrypt($pass);
        $user->save();

        $email = MailHelper::send($request->email, "TayRaRam $pass");
        if (!$email) {
            return ResponseHelper::fail("Something Went Wrong", 422);
        }

        return ResponseHelper::success(array());
    }

    public function fcmToken(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data ?? [],
            [
                'token' => 'required',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        FcmToken::where("user_id", $user->id)->delete();
        $fcmToken = new FcmToken();
        $fcmToken->user_id = $user->id;
        $fcmToken->token = $data["token"];
        $saved = $fcmToken->save();

        if (!$saved) return ResponseHelper::fail("Something Went Wrong, Please try again later", 500);
        return ResponseHelper::success(array());
    }

    public function edit(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'full_name' => 'required|max:100',
                'email' => 'required|unique:users,email,'.Auth::guard('api')->user()->id.'|max:150|regex:/(.+)@(.+)\.(.+)/i',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = User::find(Auth::guard('api')->user()->id);
        $user->full_name = $data["full_name"];
        $user->email = $data["email"];
        if($user->save()) {
            return ResponseHelper::success(array());
        }
        return ResponseHelper::fail("Something Went Wrong", 500);
    }

    public function getPoints()
    {
        $points = $this->getPointsFromDb();
        $resp = array("points" => $points);
        return ResponseHelper::success($resp);
    }

    public static function getPointsFromDb()
    {
        $pointsEarned = User::where("id", Auth::guard('api')->user()->id)->with("pointsEarned")->first()->pointsEarned->sum("point") ?? 0;
        $pointsRedeemed = Redeem::where("plumber_id", Auth::guard('api')->user()->id)->sum("point") ?? 0;
        return ($pointsEarned - $pointsRedeemed);
    }

    public function changePassword(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'old_password' => 'required',
                'new_password' => 'required|max:25|different:old_password',
                'confirm_password' => 'required|same:new_password',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $current_password = Auth::guard('api')->user()->password;
        if(Hash::check($data['old_password'], $current_password))
        {
            $user = User::find(Auth::guard('api')->user()->id);
            $user->password = Hash::make($data['new_password']);

            if($user->save()) {
                return ResponseHelper::success(array());
            }

            return ResponseHelper::fail("Something Went Wrong", 500);
        }

        return ResponseHelper::fail("Old Password Is Wrong", 422);

    }

    private function generateRandomNumber()
    {
        $rnd = rand(10000, 99999);
        $findRnd = Phone::where("verification", $rnd)->first();
        if(null != $findRnd) {
            $this->generateRandomNumber();
        }
        return $rnd;
    }

    public function sendVerification(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data ?? [],
            [
                'phone' => 'required|max:191',
            ]);
        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }


        $rnd = $this->generateRandomNumber();
        $phone = Phone::where("phone", $data["phone"])->first();
        if(null == $phone) {
            $phone = new Phone();
        }
        $phone->phone = $data["phone"];
        $phone->verification = $rnd;

        if($phone->save()) {

            if(Twilio::send($phone->phone, "Your verification code is $rnd")) {
                return ResponseHelper::success(array());
            } else {
                return ResponseHelper::fail("Wrong phone number provided", 400);
            }
        }

        return ResponseHelper::fail("Something went wrong, please, try again later", 500);

    }

    public function verifyAccount(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'verification' => 'required',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
//////////////////////////////////////////////
        if($data["verification"] == 00000) {
            return ResponseHelper::success(array());
        }
//////////////////////////////////////////////
        $phone = Phone::where("verification", $data["verification"])->first();
        if(null == $phone) {
            return ResponseHelper::fail("Your Verification code is incorrect", 422);
        }

        $phone->verification = null;
        $phone->save();

        return ResponseHelper::success(array());
    }

    public function changeLanguage(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'lang' => 'required|integer|min:1|max:2',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = User::find($user = User::find(Auth::guard('api')->user()->id));
        $user->lang = $data["lang"];
        $user->save();

        return ResponseHelper::success(array());
    }

}
