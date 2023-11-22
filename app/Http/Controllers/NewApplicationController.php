<?php

namespace App\Http\Controllers;

use App\Models\AdditionalDoc;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceDate;
use App\Models\Nomination;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LiquorBoardRequest;

class NewApplicationController extends Controller
{
    public $get_reg_num_or_id_number = '';

    public function create(){
        
        if(request('variation') && request('variation') == 'Company'){
            $comp = Company::whereId(request('id'))->first(['reg_number']);
            $this->get_reg_num_or_id_number = $comp->reg_number;
          }elseif(request('variation') && request('variation') == 'Individual'){
           $person = People::whereId(request('id'))->first(['id_or_passport']);
           $this->get_reg_num_or_id_number = $person->id_or_passport;
          }

        $persons = People::pluck('full_name','id');
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::orderBy('licence_type','ASC')->get();

        return Inertia::render('New Applications/Index',[
            'persons' => $persons,
             'companies' => $companies,
             'licence_dropdowns' => $licence_dropdowns,
             'get_reg_num_or_id_number' => $this->get_reg_num_or_id_number
        ]);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'trading_name' => 'required',
                'licence_type' => 'required|exists:licence_types,id',
                'belongs_to' => 'required|in:Company,Individual',
                'board_region' => 'required' 
               ]);
        
               if($request->belongs_to === 'Company'){
                $request->validate(['company' => 'required|exists:companies,id']);
               }else{
                $request->validate(['person' => 'required|exists:people,id']);
               }
        
               $licence = Licence::create([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->belongs_to === 'Company' ? $request->company : NULL,
                'people_id' => $request->belongs_to === 'Individual' ? $request->person: NULL,
                'board_region' => $request->board_region,
                'is_new_app' => true,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'slug' => sha1(now())
               ]);
               if($licence){
                return to_route('view_registration',['slug' => $licence->slug])->with('success','New App created successfully.');
               }
               
        } catch (\Throwable $th) {
            //throw $th;
           return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function update(Request $request,$slug){
        
      
        try {
            $request->validate([
                'trading_name' => 'required'
               ]);             
        
               $licence = Licence::whereSlug($slug)->update([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->company_id,
                'people_id' => $request->person_id,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'licence_number' => $request->licence_number,
                'client_number' => $request->client_number,
                'latest_renewal' => $request->latest_renewal,
                'licence_date' => $request->licence_date,
                'licence_issued_at' => $request->licence_date,
                'postal_code' => $request->postal_code,
               ]);
               if($licence){
                return back()->with('success','Licence updated successfully.');
               }
               
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function view_registration(Request $request){
        $licence = Licence::with('company','licence_stage_dates','documents')->whereSlug($request->slug)->first();
      
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Licence')->where('model_id',$licence->id)->get();
       
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString();        
        $additional_docs = AdditionalDoc::get();

        return Inertia::render('New Applications/Registration',[
            'licence' => $licence,
            'additional_docs' => $additional_docs,
            'liqour_board_requests' => $liqour_board_requests,
            'tasks' => $tasks]);
    }

    public function updateRegistration(Request $request, $slug){
       try {
        $licence = Licence::whereSlug($slug)->first();
        $status = '';
        if($request->status){
            if($request->unChecked){
                $status = $request->prevStage;
            }else{
                $status = $request->status[0];
            }
        }
        
        //Start new nomination
        if($status >= 2300){
            $nom = Nomination::where('year',now()->format('Y'))->where('licence_id', $licence->id)->first();
            if(is_null($nom)){
                Nomination::create([//begin nomination
                    'licence_id' => $licence->id,
                    'year' => now()->format('Y'),
                    'slug' => sha1(now())
                ]);
                
            }

            //If stage is issued then its no longer a new app.
            $licence->update(['is_new_app' => false]);
        }

        // $licence->update([
        //     'renewal_amount' => $request->renewal_amount,
        //     'status' => $status <= 0 ? NULL : $status,
        //    ]);
           
           return back()->with('success','Status Updated successfully');
       } catch (\Throwable $th) {
         //throw $th;
         return back()->with('error','An error occured while updating.');
       }
       
    }

public function updateRegistrationDate(Request $request, $slug)
{
    try {
        //Validate
        $request->validate([
            'dated_at' => 'required',
            'stage'  => 'required',
            'licence_id' => 'required|exists:licences,id'
        ]);
        
        if($request->stage == 'Licence Issued'){
              //If stage is issued then its no longer a new app.
              Licence::whereSlug($slug)->update(['is_new_app' => false, 'licence_date' => $request->dated_at]);
              
        }
        LicenceDate::create([
            'dated_at' => $request->dated_at,
            'licence_id' => $request->licence_id,
            'stage' => $request->stage,
           ]);
       return back()->with('success','Date updated successfully.');
    } catch (\Throwable $th) {
        throw $th;
        return back()->with('error','Error updating date.');
    }
}


}
