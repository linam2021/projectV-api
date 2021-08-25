<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
            if ($validator->fails())
                return $this->sendError('Validate Error', $validator->errors());
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('password')->accessToken;
            return $this->sendResponse($success, 'User registered successfully');
        } catch (\Exception $exception) {
            return $this->sendError(['message' => $exception->getMessage()], ['status' => 404]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails())
                return $this->sendError('Validate Error', $validator->errors());
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('password')->accessToken;
                return $this->sendResponse($success, 'User login successfully');
            } else
                return $this->sendError('Unauthorised', ['error', 'Unauthorised']);
        } catch (\Exception $exception) {
            return $this->sendError(['message' => $exception->getMessage()], ['status' => 404]);
        }
    }

    // added function user to verify email
    public function user()
    {
        return Auth::User();
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->sendResponse('success', ['message' => 'User logged out successfully'],);
        } catch (\Exception $exception) {
            return $this->sendError(['message' => $exception->getMessage()], ['status' => 404]);
        }
    }
}
