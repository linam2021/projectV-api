<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function searchUsers($info)
    {
        $users = User::where('email', 'like', '%' . $info . '%')
            ->orWhere('id', 'like', '%' . $info . '%')
            ->orWhere('firstname', 'like', '%' . $info . '%')
            ->orWhere('lastname', 'like', '%' . $info . '%')
            ->orWhere('phone', 'like', '%' . $info . '%')
            ->orWhere('country', 'like', '%' . $info . '%')
            ->orWhere('telegram', 'like', '%' . $info . '%');

        return $users;
    }

    public function permisionAdmin($id)
    {
        $user = User::find($id);

        if (!$user) redirect()->back()->with('message', 'هذا الحساب غير موجود');

        if (Auth::user()->email === 'thegameoflife.tg@gmail.com') {
            if ($user->is_admin === 0) {
                $user->is_admin = 1;
                $user->save();
                redirect()->back()->with('message', 'تم تغيير هذا الحساب إلى الادمن بنجاح');
            }

            if ($user->is_admin === 1) {
                $user->is_admin = 0;
                $user->save();
                redirect()->back()->with('message', 'تم تغيير هذا الحساب إلى المستخدم بنجاح');
            }
        }

        return redirect()->back()->with('message', 'لا يمكنك تغيير الصلاحية يخول فقط للأدم الرئيسي فعل ذلك');
    }
}
