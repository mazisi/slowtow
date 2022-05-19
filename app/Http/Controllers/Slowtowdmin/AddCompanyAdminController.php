<?php

namespace App\Http\Controllers\Slowtowdmin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailCredentials;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AddCompanyAdminController extends Controller
{
    public function store(Request $request){
       $request->validate([
           'full_name'=> 'required|string|max:200',
           'company_admin_email'=> 'required|email|max:255',
           'company_id'=> 'required|exists:companies,id'
       ]);
       $password = Str::random(6);
       $user = User::create([
        'name' => $request->full_name,
        'email' => $request->company_admin_email,
        'password' => Hash::make($password)
       ]);
       if($user){
        $user->assignRole('company-admin');
        $user->company()->attach($request->company_id);
       }
      $mailables = [
          'full_name' => $request->full_name,
          'email' => $request->company_admin_email,
          'password' => $password
      ];
       Mail::to('mazisimsebele18@gmail.com')->send(new SendMailCredentials($mailables));
       return back()->with('success','User created successfully.');
    }
}
