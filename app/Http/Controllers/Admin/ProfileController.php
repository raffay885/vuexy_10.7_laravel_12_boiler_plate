<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $view = 'admin.profile.';

    public function profile()
    {
        $user = Auth::user();
        return view($this->view . 'profile', get_defined_vars());
    }

    public function updateProfile(ProfileRequest $request)
    {
        try{
            $user = Auth::user();
            $user->fill($request->validated())->save();
            return response()->json(['status' => true, 'message' => 'Profile updated successfully'], 200);
        }catch(\Exception $e){
            Log::error('Profile Update Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    public function changePassword()
    {
        return view($this->view . 'changePassword', get_defined_vars());
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        try{
            $validated = $request->validated();
            $user = Auth::user();

            if(!Hash::check($validated['current_password'], $user->password)){
                return response()->json(['status' => false, 'message' => 'Current password is incorrect.'], 400);
            }

            if(Hash::check($validated['password'], $user->password)){
                return response()->json(['status' => false , 'message' => 'Uh-oh! New Password can not be same as Old Password.'], 400);
            }

            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json(['status' => true , 'message' => 'Password Updated Successfully!'], 200);
        }catch(\Exception $e){
            Log::error('Password Update Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }
}
