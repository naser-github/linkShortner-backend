<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function editProfile($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            $response = [
                'msg' => 'Try Again!!'
            ];
            return response($response, 404);
        }

        $name = explode(" ", $user->name, 2);

        $response = [
            'user' => $user,
            'name' => $name
        ];
        return response($response, 201);
    }

    public function updateProfile(Request $request, $id)
    {
        request()->validate([
            'fname' => 'required|min:3',
            'lname' => 'required',
            'phone' => 'required|min:11||max:14',
            'password' => 'present|confirmed:password_confirm',
        ]);

        $user = User::where('id', $id)->first();

        if (!$user) {
            return back();
        }

        $name = $request->input('fname') . " " . $request->input('lname');
        $phone = $request->input('phone');

        $user->name = $name;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user_profile = UserProfile::where('fk_user_id', $id)->first();
        $user_profile->user_phone = $phone;
        $user_profile->save();

        $response = [
            'msg' => 'Profile details has been updated'
        ];
        return response($response, 201);
    }
}
