<?php

namespace App\Http\Controllers\API;

use App\Models\Password_reset;
use App\Models\User;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Str;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ForgotResetController extends BaseController
{
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails())
                return $this->sendError($validator->errors());
            $email = $request->input('email');
            if (User::where('email', $email)->doesntExist())
                return $this->sendError('Email does not exists');
            $newtoken=str::random(6);
            $oldTokenEmail = Password_reset::where('email', $email)->first();
            if ($oldTokenEmail)
                Password_reset::where('email', $email)->update(['token' => $newtoken]);
            else {
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $newtoken
                ]);               
            }
            Mail::send('Mails.forgot', ['token' => $newtoken], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Password Reset Code');
            });
            return $this->sendResponse('Success','Password reset code is sent successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function passwordReset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
            if ($validator->fails())
                return $this->sendError($validator->errors());
            $token = $request->input('token');
            $email = $request->input('email');
            $passwordResetEmail = Password_reset::where('email', $email)
                ->first();
            $passwordResetToken = Password_reset::where('token', $token)
                ->where('email', $email)
                ->first();
            if (!$user = User::where('email', $email)->first())
                return $this->sendError('Email does not exists');
            if (!$passwordResetEmail)
                return $this->sendError('Invalid email');
            if (!$passwordResetToken)
                return $this->sendError('Invalid token');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            DB::table('password_resets')->where('email', $request['email'])->where('token', $request['token'])->delete();
            return $this->sendResponse('Success','Password is reset successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
