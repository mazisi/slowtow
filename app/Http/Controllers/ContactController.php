<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller{

    public function index(){
        $contacts = Contact::latest()->get();
        return Inertia::render('Contacts/Contact',['contacts' => $contacts]);
    }

    public function create(){
        return Inertia::render('Contacts/CreateContact');
    }

    public function store(Request $request){
        $request->validate([
         "csv_file" => "required|file"
        ]);
       $csv_file = $request->csv_file->store('contacts','public');
       $valid_extension = array("csv");
       $extension = $request->csv_file->getClientOriginalExtension();

       if(in_array(strtolower($extension),$valid_extension)){
            if (($handle = fopen ( public_path () . '/storage/'.$csv_file, 'r' )) !== FALSE) {
                $flag = true;
            
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                  if($request->header == '1'){
                    if($flag) { 
                        $flag = false; continue;
                     }
                    }
                    $csv_data = new Contact ();
                    $csv_data->first_name = $data [0];
                    $csv_data->middle_name = $data [1];
                    $csv_data->last_name = $data [2];
                    $csv_data->business_phone = $data [3];
                    $csv_data->mobile_phone = $data [4];
                    $csv_data->email = $data [5];
                    $csv_data->save ();
                }
            
                fclose ( $handle );
                return to_route('contacts')->with('success','Contacts uploaded successfully.');
            }else{
                return to_route('contacts')->with('error','Error uploading contacts.');
            }
        }else{
            return to_route('contacts')->with('error', 'Uploaded file is not a valid csv file.');
          }
    
    }

    public function destroy($id){
       $contact = Contact::find($id);
       if($contact->delete()){
        return redirect(route('contacts'))->with('success','Contact deleted successfully.');
       }
       return redirect(route('contacts'))->with('error','Error deleting contact.');
    }

    public function destroyAll()
    {
        Contact::truncate();
        return redirect(route('contacts'))->with('success','All contacts deleted successfully.');
    }

}
