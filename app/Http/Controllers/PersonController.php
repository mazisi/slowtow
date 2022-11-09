<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ValidatePeople;
use App\Models\PeopleDocument;
use Illuminate\Support\Facades\Redirect;

class PersonController extends Controller
{
    public function index(Request $request){
        if(!empty($request->term) && $request->active_status == 'Active'){

            $people = People::where('full_name','LIKE','%'.$request->term.'%')
                            ->where('licence_status','1')
                            ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')
                            ->get();

        }elseif($request->active_status == 'All' && empty($request->term)){
           
            $people = People::latest()->get();
        
        }elseif(!empty($request->term) && empty($request->active_status)){
            $people = People::where('full_name','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_2','LIKE','%'.$request->term.'%')
                            ->get();
        
        }elseif(!empty($request->term)  && $request->active_status == 'Inactive'){
                    $people = People::where('full_name','LIKE','%'.$request->term.'%')
                            ->whereNull('active')
                            ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_2','LIKE','%'.$request->term.'%')
                            ->get();
       }elseif(empty($request->term)  && $request->active_status == 'Active'){
        $people = People::where('active',1)->get();
        }else{
            $people = People::whereNull('id')->get();
        }
        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(ValidatePeople $request){
        
        $person = People::create([
        'full_name'=> $request->name.' '.$request->surname,
        'active'=> $request->active,
        'date_of_birth' => $request->date_of_birth,
        'id_or_passport' => $request->id_or_passport,
        'email_address_1' => $request->email_address_1,
        'email_address_2' => $request->email_address_2,
        'cell_number' => $request->cell_number,
        'telephone' => $request->telephone,
        'passport_valid_until' => $request->passport_valid_until,
         'slug' => Str::replace(' ','_',$request->name).' '.Str::replace(' ','_',$request->surname).'_'.sha1(time()),
        ]);
        
        if($person){
           return Redirect::route('people')->with('success','Person created succesfully.');
        }
        return Redirect::route('people')->with('error','Error creating person.');
    }

    public function show($slug){
        $person = People::with('nominations','people_documents')->whereSlug($slug)->first();
        $id_document = PeopleDocument::where('people_id',$person->id)->where('doc_type','ID Document')->first();
        $police_clearance = PeopleDocument::where('people_id',$person->id)->where('doc_type','Police Clearance')->first();
        $passport_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Passport')->first();
        $work_permit_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Work Permit')->first();
        $tasks = Task::where('model_type','Person')->where('model_id',$person->id)->whereUserId(auth()->id())->get();
        return Inertia::render('People/ViewPerson',[
            'person' => $person,
            'tasks' => $tasks,
           'id_document' => $id_document,
            'police_clearance' => $police_clearance,
            'passport_doc' => $passport_doc,
            'work_permit_doc' => $work_permit_doc]);
            }

    public function update(ValidatePeople $request){
        $person = People::whereSlug($request->slug)->update([
        'full_name'=> $request->name,
        'active'=> $request->active,
        'date_of_birth' => $request->date_of_birth,
        'id_or_passport' => $request->id_or_passport,
        'email_address_1' => $request->email_address_1,
        'email_address_2' => $request->email_address_2,
        'cell_number' => $request->cell_number,
        'telephone' => $request->telephone,
        'passport_valid_until' => $request->passport_valid_until,
        ]);
        if($person){
           return back()->with('success','Person updated succesfully.');
        }
        return back()->with('error','Error updating person.');
    }

    public function destroy($slug){
        $del = People::whereSlug($slug)->firstOrFail();
        if($del->delete()){
            return Redirect::route('people')->with('success','Person deleted succesfully.');
        }
        return Redirect::route('people')->with('error','Error updated person.');
    }


    public function uploadDocument(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf",
            "people_id" => "required|exists:people,id",
            "doc_type" => "required|in:Work Permit,Passport,Police Clearance,ID Document"
            ]);

            $fileModel = new PeopleDocument;
            $fileName = $request->document->getClientOriginalName();
            $filePath = $request->file('document')->storeAs('peopleDocuments', $fileName, 'public');
            $fileModel->document_name = $fileName;
            $fileModel->document = $fileName;
            $fileModel->people_id = $request->people_id;
            $fileModel->doc_type = $request->doc_type;
            $fileModel->expiry_date = $request->doc_expiry;
            $fileModel->path = "app/public/";
            $fileModel->slug = sha1(time());
            
       if($fileModel->save()){
        return back()->with('message','Document uploaded successfully.');
       }
        return back()->with('success','Error uploading document.');
    
    }

    public function deleteDocument($slug){
        $model = PeopleDocument::whereSlug($slug)->first();
        if(!is_null($model->document)){
            unlink(public_path('storage/'.$model->document));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }

    
}
