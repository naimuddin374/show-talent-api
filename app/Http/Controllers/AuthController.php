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
        $user = User::where(['email' => $credentials['email'], 'status' => 1, 'type' => 1])->first();
        if(!$user){
            return response()->json(["message" => "Invalid Credentials."], 401);
        } else if(!Hash::check($credentials['password'], $user->password)){
            return response()->json(["message" => "Invalid Credentials."], 401);
        }else{
            $customClaims = [
                'id' => $user->id,
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

    public function adminAuthenticate(Request $request)
    {
        $credentials = $request->all();
        $user = User::where(['email' => $credentials['email'], 'status' => 1])->first();
        if(!$user){
            return response()->json(["message" => "Invalid Credentials."], 401);
        } else if(!Hash::check($credentials['password'], $user->password)){
            return response()->json(["message" => "Invalid Credentials."], 401);
        } else if($user->type != 3 && $user->type != 2){
            return response()->json(["message" => "Invalid Credentials."], 401);
        }else{
            $customClaims = [
                'id' => $user->id,
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

    public function refreshToken(){
        $auth = auth()->user();
        $user = User::where(['id' => $auth['id']])->first();
        $customClaims = [
            'id' => $user->id,
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
            return response()->json(["message" => "Authentication Field!"], 401);
        }else{
            return response()->json(["message" => "Login Successful.", 'token' => 'Bearer '.$token], 200);
        }
    }

    public function forgotPassword(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            "email" => "required",
        ]);
        if ($validator->fails()) {
            return back()->with($validator->errors());
        }
        $sel_user = User::where(['email' => $post['email']])->first();
        if(!$sel_user){
            return response()->json(["message" => "Invalid email address."], 400);
        }else if($sel_user->status == 0){
            return response()->json(["message" => "Your account has been pending, please contact our support."], 400);
        }else if($sel_user->status == 2){
            return response()->json(["message" => "Your account has been blocked, please contact our support."], 400);
        }else if($sel_user->status == 3){
            return response()->json(["message" => "Your account has been rejected, please contact our support."], 400);
        }else{
            $hash_key = $this->encryptor("encrypt", $sel_user->id);
            User::where(['id' => $sel_user->id])->update(['reset_code' => $hash_key]);

            $url = "http://showtalent.btlbd.tk/reset/password/{$hash_key}";
            
            $mailInfo = array();
            $mailInfo['name'] = $sel_user->name;
            $mailInfo['email'] = $sel_user->email;
            $mailInfo['url'] = $url;
            // return view('forgot-password')->with('mailData', $mailInfo);
            Mail::send('forgot-password', $mailInfo, function($message) use ($mailInfo){
                $message->to($mailInfo['email']);
                // $message->to("naimuddin374@gmail.com");
                $message->subject("Forgot Password");
                $message->from('website@beatnik.technology');
            });
            return response()->json(["message" => "Please check your email and reset your password.", 'url' => $url], 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return back()->with($validator->errors());
        }
        $sel_user = User::where('reset_code', $post['hash_key'])->first();
        if(!$sel_user){
            return response()->json(["message" => "Invalid Link."], 400);
        }else{
            User::where('id', $sel_user->id)->update(['password' => Hash::make($post['password']), 'reset_code' => NULL]);
            return response()->json(["message" => "Your password has been updated successfully."], 201);
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