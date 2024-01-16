<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;
use App\Events\LogUserActivity;
use App\Models\TemporalLicence;
use App\Models\LiquorBoardRequest;
use App\Models\TemporalLicenceDocument;

class TemporalLicenceController extends Controller
{
    public function index(){
        
        $licences = TemporalLicence::with('company','people')
        ->when(request('term'), 
            function ($query){ 
                return $query->whereHas('company', function($query){
                    $query->where('name', 'like', '%'.request('term').'%');
                })->orWhereHas('people', function($query){
                    $query->where('full_name', 'like', '%'.request('term').'%');                                      
                          })
                ->orWhere('liquor_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('start_date','LIKE','%'.request('term').'%')
                ->orWhere('end_date','LIKE','%'.request('term').'%')
                ->orWhere('event_name','LIKE','%'.request('term').'%');
            
            })
        
                ->latest()->paginate(20)->withQueryString();

        return Inertia::render('TemporalLicences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

    public function store(Request $request){
       $request->validate([
           'event_name' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           'latest_lodgment_date'=> 'required',
           'belongs_to' => 'required|in:Individual,Company'
           ]);
           if(is_null($request->person)){
            $request->validate(['company' => 'required|exists:companies,id']);
            $temp = TemporalLicence::create([
                'company_id' => $request->company,
                'event_name' => $request->event_name,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'application_type' => $request->application_type,
                'address' => $request->address,
                'belongs_to' => $request->belongs_to,
                'latest_lodgment_date'  => $request->latest_lodgment_date,
                'slug' => sha1(time()),
                ]);

           }else{
            $request->validate(['person' => 'required|exists:people,id']);
            $temp = TemporalLicence::create([
                'people_id' => $request->person,
                'event_name' => $request->event_name,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'application_type' => $request->application_type,
                'belongs_to' => $request->belongs_to,
                'address' => $request->address,
                'latest_lodgment_date'  => Carbon::parse($request->latest_lodgment_date)->format('m-d-Y'),
                'slug' => sha1(time()),
                ]);

           }
           
           if($temp){
              return to_route('view_temp_licence',['slug' => $temp->slug])->with('success','Temporary Licence issued successfully.');
           }
           return back()->with('error','AN unknown error occured while creating Temporary Licence.');
    }

    

    public function show($slug){
        $licence = TemporalLicence::with('company','people')->whereSlug($slug)->first();

        return Inertia::render('TemporalLicences/ViewTemporalLicence',['licence' => $licence]);
      }

    public function update(Request $request){
        $temp = TemporalLicence::whereSlug($request->slug)->first();        
            $temp->update([
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                "application_type" => $request->application_type,
                "address" => $request->address,
                "event_name" => $request->event_name
                ]);
    
              
            if($temp){
               return back()->with('success','Temporary Licence updated successfully.');
            }
            return back()->with('error','An unknown error occured while updating Temporary Licence.');
     }

    public function processApplication(Request $request){
        $licence = TemporalLicence::with('company','people','temp_documents')->whereSlug($request->slug)->first();
        $tasks = Task::where('model_type','Temporal Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString();
    



        return Inertia::render('TemporalLicences/ProcessApplication',
            ['licence' => $licence,'tasks' => $tasks]);

     }









    public function update_prepared_temp_app(Request $request,$slug){
        try {
            $status = '';
            if($request->status){
                if($request->unChecked){
                    $status = $request->prevStage;
                }else{
                    $status = $request->status[0];
                }
            }
    
            TemporalLicence::whereSlug($slug)->update(["status" => $status]);   
               
            return back()->with('success','Temporary Licence updated successfully.');
         
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','An unknown error occured while updating Temporary Licence.');
        }
        
            
     }

     public function updateDates(Request $request, $slug){
        try {
// dd($request);
            $fieldToUpdate = '';
            switch ($request->stage) {
                case 'Client Paid':
                    $fieldToUpdate = 'client_paid_at';
                    break;

                case 'Payment To The Liquor Board':
                    $fieldToUpdate = 'payment_to_liquor_board_at';
                    break;

                case 'Temporary Licence Lodged':
                    $fieldToUpdate = 'logded_at';
                    break;

                case 'Temporary Licence Issued':
                    $fieldToUpdate = 'issued_at';
                    break;

                case 'Temporary Licence Delivered':
                    $fieldToUpdate = 'delivered_at';
                    break;

                default:
                    // Handle the default case, if needed.
                    break;
            }

            TemporalLicence::whereSlug($slug)->update([$fieldToUpdate => $request->dated_at]);
                return back()->with('success','Date updated successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','An unknown error occured while updating date.');
        }
    }

     public function destroy($slug){
        try {
            $licence = TemporalLicence::whereSlug($slug)->first();
            $activity = 'Deleted Temporal Licence: ' . $licence->event_name;
            event(new LogUserActivity(auth()->user(), $activity));
            if($licence->delete()){
               return to_route('temp_licences')->with('success','Temporary Licence deleted successfully.');
            }
            
        } catch (\Throwable $th) {
            return to_route('temp_licences')->with('error','AN unknown error occured while deleteing Temporary Licence.');
        }

     }
}
