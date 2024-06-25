<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NotifyMailer;
use App\Models\People;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    
    public function update(Request $request)
    {
        try {

            if(auth()->user()->email != $request->email || auth()->user()->contact != $request->contact){
                Mail::to('mazisimsebele18@gmail.com')->send(new NotifyMailer(auth()->user(), 'profile'));
            }
            $user = User::whereId(auth()->id())->first();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact
            ]);
            if($user){
                $person = People::whereId($user->people_id)->first();
                
                $person->update([
                    'full_name' => $request->name,
                    'cell_number' => $request->contact,
                    'email_address_1' => $request->email
                ]);
                
            }
    
            return back()->with('success','Profile updated succesfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
