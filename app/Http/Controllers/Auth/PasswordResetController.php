<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    
    public function index(){
        $user = User::whereId(auth()->id())->first();
        return Inertia::render('Auth/ResetPassword', [
            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            "old_password"=> "required",
            "new_password"=> "required|confirmed"
        ]);
        
          if(Hash::check($request->old_password, $request->new_password)){
            auth()->user()->update(['password' => Hash::make($request->new_password)]);
            return back()->with('success', 'Password updated successfully');
            }else{
                return back()->with('error', 'Old Password incorrect');
            }
        
              
          }

}
