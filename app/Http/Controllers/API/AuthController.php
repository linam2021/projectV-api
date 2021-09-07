<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
            if ($validator->fails())
                return $this->sendError($validator->errors());
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('password')->accessToken;
            //send the verify code
	        $token = Str::random(6);   
		    DB::table('users')->where('email', $request['email'])->update([
			    'is_verified' => $token,
		    ]);
		    $email = $request['email'];
		    Mail::send('Mails.verify', ['token' => $token], function ($message) use ($email) {
			    $message->to($email);
			    $message->subject('Verification Email Code');
		    });           
            return $this->sendResponse($success, 'User is registered successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function verifyEmail(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'email' => 'required | email',
		    'token' => 'required',
	    ]);
        if($validator->fails())
            return $this->sendError($validator->errors());
	    $user = User::where('email', $request['email'])->first();
	    $token = $request['token'];
	    if($user->is_verified == $token){
		    $user->is_verified = 1;
		    $user->markEmailAsVerified();
		    $user->save();
		    return $this->SendResponse('Success','Email is verified successfully');
	    }
        if ($user->is_verified == 1) 
            return $this->sendError('Email is already Verified');
	    return $this->sendError('The Email Verification is failed, Enter correct verification code');
    }

    public function resendVerificationEmailCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        $inputs = $request->input();
        $user=DB::table('users')->where('email' , $inputs['email'])->first();
        if ($user==null)
            return $this->sendError("The email is not found");
        else
        {
            if (strcmp($user->is_verified,'1') != 0)
            {
                $token = Str::random(6);
                DB::table('users')->where('email', $inputs['email'])->update([
                    'is_verified' => $token,
                ]);
                $email = $request['email'];
                Mail::send('Mails.verify', ['token' => $token], function ($message) use ($email){
                    $message->to($email);
                    $message->subject('Resend Verification Email Code');
                });
            }
            else
              return $this->sendError("The email was verified previously");
        }
        return $this->sendResponse('Success', 'The verification email code is resend successfully');
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails())
                return $this->sendError($validator->errors());
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                if($user->is_verified != 1)
			        return $this->SendError('Verify your email');
                $success['token'] = $user->createToken('password')->accessToken;
                return $this->sendResponse($success, 'User login successfully');
            } else
                return $this->sendError('Incorrect email or password');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function user()
    {
        try {
            return Auth::User();
        }catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->sendResponse('Success', 'User is logged out successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
