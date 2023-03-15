<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ValidatePeople;
use App\Models\Licence;
use App\Models\PeopleDocument;
use Illuminate\Support\Facades\Redirect;

class PersonController extends Controller
{
    public function index(){
        $people = People::when(request('term') && request('active_status') === 'Active', 
            function ($query){
                $query->where('active','1')
                ->orWhere(function ($query) {
                    $query->where('full_name','LIKE','%'.request('term').'%')
                    ->where('email_address_1','LIKE','%'.request('term').'%')
                    ->where('email_address_2','LIKE','%'.request('term').'%');
                });
            
            })

            ->when(request('term') && request('active_status') === 'Inactive', 
                function ($query){
                    
                    $query->where('active','0')
                          ->orWhere(function ($query) {
                            $query->where('full_name','LIKE','%'.request('term').'%')
                            ->where('email_address_1','LIKE','%'.request('term').'%')
                            ->where('email_address_2','LIKE','%'.request('term').'%');
                        });
                
                })

                ->when(request('term') && !request('active_status'), 
                    function ($query){ 
                            $query->where('full_name','LIKE','%'.request('term').'%')
                                  ->orWhere('email_address_1','LIKE','%'.request('term').'%')
                                  ->orWhere('email_address_2','LIKE','%'.request('term').'%');
                        
                    
                    })
                

            ->when(!request('term') && request('active_status') ==='Inactive', 
                function ($query){
                    return $query->where('active','0');                
                })

                ->when(!request('term') && request('active_status') ==='Active', 
                    function ($query){
                        return $query->where('active','1');                
                    })
            ->latest()->paginate(20)->withQueryString();

        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(Request $request){
       
        $request->validate([
            'name' => 'required|string|max:200',
            'id_or_passport' => 'required|unique:people,id_or_passport'
        ]);
        $person = People::create([
        'full_name'=> $request->name.' '.$request->surname,
        'date_of_birth' => $request->date_of_birth,
        'id_or_passport' => $request->id_or_passport,
        'email_address_1' => $request->email_address_1,
        'email_address_2' => $request->email_address_2,
        'cell_number' => $request->cell_number,
        'telephone' => $request->telephone,
        'passport_valid_until' => $request->passport_valid_until,
         'slug' => sha1(time()),
        ]);
        
        if($person){
           return Redirect::route('view_person', ['slug' => $person->slug])->with('success','Person created succesfully.');
        }
        return Redirect::route('view_person', ['slug' => $person->slug])->with('error','Error creating person.');
    }

    public function show($slug){
        $person = People::with('nominations','people_documents')->whereSlug($slug)->first();
        $id_document = PeopleDocument::where('people_id',$person->id)->where('doc_type','ID Document')->first();
        $police_clearance = PeopleDocument::where('people_id',$person->id)->where('doc_type','Police Clearance')->first();
        $passport_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Passport')->first();
        $work_permit_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Work Permit')->first();
        $fingerprints = PeopleDocument::where('people_id',$person->id)->where('doc_type','Fingerprints')->first();
        $tasks = Task::where('model_type','Person')->where('model_id',$person->id)->latest()->paginate(4)->withQueryString();
        return Inertia::render('People/ViewPerson',[
            'person' => $person,
            'tasks' => $tasks,
            'id_document' => $id_document,
            'police_clearance' => $police_clearance,
            'passport_doc' => $passport_doc,
            'work_permit_doc' => $work_permit_doc,
            'fingerprints' => $fingerprints
           ]);
            }

    public function update(ValidatePeople $request){
        
        $person = People::whereSlug($request->slug)->update([
        'full_name'=> $request->name,
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
            $del_licences = Licence::where('people_id',$del->id)->get(['id']);
            foreach ($del_licences as $delete_lic) {
                $delete_lic->delete();
            }

            return Redirect::route('people')->with('success','Person deleted succesfully.');
        }
        return Redirect::route('people')->with('error','Error updated person.');
    }


    public function uploadDocument(Request $request){
        $request->validate([
            'document' => 'mimes:jpeg,jpg,png,pdf',
            "people_id" => "required|exists:people,id",
            "doc_type" => "required|in:Work Permit,Passport,Police Clearance,ID Document,Fingerprints"
            ]);

            $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
            $fileModel = new PeopleDocument;
            $fileName = Str::limit(sha1(now()),7).str_replace('-', '_',$removeSpace);
            $filePath = $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
            $fileModel->document_name = $fileName;
            $fileModel->document = $fileName;
            $fileModel->people_id = $request->people_id;
            $fileModel->doc_type = $request->doc_type;
            $fileModel->expiry_date = $request->doc_expiry;
            $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
            $fileModel->slug = sha1(time());
            
       if($fileModel->save()){
        return back()->with('message','Document uploaded successfully.');
       }
        return back()->with('success','Error uploading document.');
    
    }

    public function deleteDocument($slug){
        $model = PeopleDocument::whereSlug($slug)->first();
        if(!is_null($model->document)){
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
        return back()->with('error','An error occurred while deleting document');
    }


    public function updateActiveStatus(Request $request,$slug){
        $person =People::whereSlug($slug)->first();
        if($request->unChecked){
          $person->update(['active' => 0]);
        }else{
            $person->update(['active' => $request->status]);
        }
        return back()->with('success','Saved.');
    }
    
}
