<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;
use App\Models\LicenceTransfer;
use Illuminate\Validation\Rule;
use App\Actions\PeopleFilterAction;
use App\Http\Requests\ValidatePeople;

class PersonController extends Controller
{
    public function index(){
        $people = (new PeopleFilterAction)->filterPeople();

        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:200',
            'id_or_passport' => [
                'required',
                'string',
                Rule::unique('people', 'id_or_passport')->whereNull('deleted_at'),
            ],
        ]);
       try {
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
               return to_route('view_person',['slug' => $person->slug])->with('success','Individual created succesfully.');
            }
            return back()->with('error','Error creating individual.');
       } catch (\Throwable $th) {
         return back()->with('error','Error creating individual.');
       }
    }

    public function show($slug){
        $person = People::with('nominations','people_documents','company')->whereSlug($slug)->first();

        $tasks = Task::where('model_type','Individual')->where('model_id',$person->id)->latest()->paginate(4)->withQueryString();
        $linked_licences = Licence::with('company')->where('people_id',$person->id)->paginate(10);

        return Inertia::render('People/ViewPerson',[
            'person' => $person,
            'tasks' => $tasks,
            'linked_licences' => $linked_licences,
           ]);
     }

    public function update(ValidatePeople $request){

       try {
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
               return back()->with('success','Individual updated succesfully.');
            }
            return back()->with('error','Error updating individual.');
       } catch (\Throwable $th) {
         return back()->with('error','Error updating individual.');
       }
    }

    public function destroy($slug){
        $delete_person = People::whereSlug($slug)->firstOrFail();
        if($delete_person->delete()){
            $del_licences = Licence::where('people_id',$delete_person->id)->get(['id']);
            foreach ($del_licences as $delete_lic) {
                $delete_lic->delete();
            }

            //delete transfers belonging to person
            $deleteTransfers = LicenceTransfer::where(function ($query) use($delete_person){
                $query->where('people_id',$delete_person->id)
                      ->orWhere('old_people_id',$delete_person->id);
             })->get(['id']);

            foreach ($deleteTransfers as $delete_transfer) {
                $delete_transfer->delete();
            }
            return to_route('people')->with('success','Individual deleted succesfully.');
        }
        return to_route('people')->with('error','Error updating individual.');
    }





    public function updateActiveStatus(Request $request,$slug){
        try {
            $person =People::whereSlug($slug)->first();
        if($request->unChecked){
          $person->update(['active' => 0]);
        }else{
            $person->update(['active' => $request->status]);
        }
        return back()->with('success','Saved.');
        } catch (\Throwable $th) {
            return back()->with('error','An error occurred.');
        }
    }

}
