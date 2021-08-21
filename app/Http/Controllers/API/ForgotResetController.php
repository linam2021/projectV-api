<?php

namespace App\Http\Controllers\API;
use App\Models\Password_reset;
use App\Models\User;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Str;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ForgotResetController extends BaseController
{
    public function forgot(Request $request)
    {
        $validator=Validator::make($request->all(),[
        	'email'=>'required|email',
        ]);
        if ($validator->fails()) {
        	return $this->sendError('Validate Error',$validator->errors());
        }
        $email = $request->input('email');
        if(User::where('email',$email)->doesntExist())
            { return $this->sendError(['massage' =>'User does not exists'], ['status'=> 404]);}

       try{
        $oldToken = Password_reset::where('email', $email)->first();
        if ($oldToken){
            $newToken = str::random(6);
            $oldToken->token = $newToken;
            $oldToken->save();
            Mail::to($email)->send(new MyTestMail($oldToken->token));
            return $this->sendResponse(['massage' =>'check your email'],['status'=> 200]);
        }
        else
        {
            $token = str::random(6);
             DB::table('password_resets')->insert([
               'email' =>$email,
               'token' =>$token
           ]);

           // Send to Email
           Mail::to($email)->send(new MyTestMail($token));
           return $this->sendResponse(['massage' =>'check your email'],['status'=> 200]);
        }

      }catch (\Exception $exception){
        return $this->sendError(['message' => $exception->getMessage()], ['status'=> 404]);
     }

    }

    public function reset(Request $request)
    {
      try{
        $validator=Validator::make($request->all(),[
        'token'=>'required',
        'email'=>'required|email',
        'password'=>'required',
        'c_password'=>'required|same:password',
     ]);
     if ($validator->fails()) {
         return $this->sendErorr('Validate Error',$validator->errors());
        }

     $token = $request->input('token');
     $email = $request->input('email');
     $passwordReset = Password_reset::where('token', $token)
     ->where('email', $email)
     ->first();
     if (!$user = User::where('email' , $email)->first()){
        return $this->sendError(['massage' =>'User does not exists'], ['status'=> 404]);
     }
     if(!$passwordReset){
           return $this->sendError(['massage' =>'Invalid token'], ['status'=> 400]);
        }
     $user->password = Hash::make($request->input('password'));
     $user->save();
        return $this->sendResponse(['massage' =>'password was changed'],['status'=> 200]);

     }catch (\Exception $exception){
        return $this->sendError(['message' => $exception->getMessage()], ['status'=> 404]);
    }
   }
}
