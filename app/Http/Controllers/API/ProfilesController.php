<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\DB;

class ProfilesController extends BaseController
{
    public function addProfile(Request $request){
        try{
            $user=Auth::user();
            if ($user->profile_id != null)
                return $this ->SendError('You have profile previously');
            $validator = Validator::make($request->all(),[
                'first_name' =>'required',
                'last_name' =>'required',
                'telegram' =>'required|unique:profiles',
                'phone' =>'required|unique:profiles',
                'country' =>'required',
                'gender' =>'required',
            ]);
            if ( $validator ->fails()){
                return $this ->sendError($validator ->errors());
            }else{
                // create profile
                $profile=Profile::create([
                    'first_name' =>$request->first_name,
                    'last_name' =>$request->last_name,
                    'telegram' =>$request->telegram,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'gender' =>$request->gender,
                ]);
                // Update user table in a column profile_id
                $user->profile_id = $profile->id;
                $user->save();
                return $this ->sendResponse ('Profile is created successfully');
            }
            }catch (\Exception $exception){
                return $this->sendError($exception->getMessage());
        }
     }

    public function showProfile(){
        try{
            $user=Auth::user();
            // find user profile 
            $profile = $user->profiles;
           // $profile = Profile::find($user_id);
            if (is_null($profile)){
                return $this ->sendError('Profile is not found');
            }else{
                return $this ->sendResponse ($profile, 'User profile is retrieved successfully');
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
  }
}
