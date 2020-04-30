<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use JWTFactory;
use App\User;


class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->all();
        $user = User::where(['email' => $credentials['email']])->first();
        if(!$user){
            return response()->json(["message" => "Invalid Credentials."], 401);
        }else{
            $customClaims = [
                'full_name' => $user->full_name,
                'name' => $user->name,
                'contact' => $user->contact,
                'email' => $user->email,
                'image' => $user->image,
                'type' => $user->type,
                'status' => $user->status,
                'balance' => $user->balance,
            ];
            $token = JWTAuth::claims($customClaims)->fromUser($user);
            if(!$token){
                return response()->json(["message" => "Invalid Credentials."], 401);
            }else{
                return response()->json(["message" => "Login Successful.", 'token' => 'Bearer '.$token], 200);
            }
        }
    }


    public function forgotPassword(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            "email" => "required|email",
        ]);
        if ($validator->fails()) {
            return back()->with($validator->errors());
        }
        $data = User::where(["email" => $post["email"]])->get();
        if(isset($data[0])){
            foreach($data as $user){
                $userKey = $this->encryptor("encrypt", $user->id);

                $mailInfo = array();
                $mailInfo['user_name'] = $user->name;
                $mailInfo['user_email'] = $user->email;
                $mailInfo['user_key'] = $userKey;

                 Mail::send('forgot-password', $mailInfo, function($message) use ($data){
                    $message->to($data['user_email']);
                    // $message->to("naimuddin374@gmail.com");
                    $message->subject("Forgot Password");
                    $message->from('website@beatnik.technology');
                });
            }
            return response()->json(["message" => "Email has been sent please check your email."], 200);
        }else{
            return response()->json(["message" => "Invalid email."], 401);
        }
    }

    public function resetPassword(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            "password" => "required",
            "confirmPassword" => "required",
        ]);
        if ($validator->fails()) {
            return back()->with($validator->errors());
        }
        $userId = $this->encryptor("decrypt", $post['userKey']);

        $data = User::where(["id" => $userId])->get();
        if(isset($data[0])){
            foreach($data as $user){
                $row = User::findOrFail($user->id);
                $row->update(['password' => md5($post['confirmPassword'])]);
            }
            return response()->json(["message" => "Your password has been updated successful."], 201);
        }else{
            return response()->json(["message" => "Invalid Link."], 401);
        }
    }

    public function encryptor($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
            //pls set your unique hashing key
        $secret_key = 'beatnik#technolgoy';
        $secret_iv = 'beatnik$technolgoy';
            // hash
        $key = hash('sha256', $secret_key);
            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
            //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
                //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}