<?php

namespace App\Http\Controllers\Slowtowdmin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Mail\SendMailCredentials;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminsController extends Controller
{
    public function index(){
      return Inertia::render('AccountAdmin/Admins');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'full_name' => 'required',
                'password' => 'required',
                'role' => 'required'
            ]);
    
           $user  = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
           ]);
    
           $mailables = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $request->password
          ];
    
           if($user){
             Mail::to($request->email)->send(new SendMailCredentials($mailables));
             return back()->with('success','Mil sent succesfully');
           }
        } catch (\Throwable $th) {
            return back()->with('success','ERROR');
        }
    }
}
