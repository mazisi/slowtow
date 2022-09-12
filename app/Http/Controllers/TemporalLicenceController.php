<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\PeopleDocument;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use App\Models\TemporalLicenceDocument;

class TemporalLicenceController extends Controller
{
    public function index(Request $request){
        $queryString = request('term');
        if(!empty($queryString) && !empty($request->withThrashed) && $request->active_status == 'Active'){

          
            $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('people', function($query) use($queryString){
                                $query->where('full_name', 'like', '%'.$queryString.'%');                                      
                                      })->whereNotNull('active')
                            ->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->withTrashed()
                            ->get();
        }elseif(empty($queryString) && empty($request->withThrashed) && $request->active_status == 'Active'){

        $licences = TemporalLicence::with(["company","people"])->whereNotNull('active')->get();

        }elseif(!empty($queryString) && !empty($request->withThrashed) ){
            
               $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('people', function($query) use($queryString){
                                $query->where('full_name', 'like', '%'.$queryString.'%');                                      
                              })->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->get();
        
        }elseif(!empty($queryString) && $request->active_status == 'Active'){
    
               $licences = TemporalLicence::with(['company','people'])
               ->orWhereHas('company', function($query) use($request){
                   $query->where('name', 'like', '%'.$queryString.'%');
               })->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
               ->orWhereHas('people', function($query) use($queryString){
                $query->where('full_name', 'like', '%'.$queryString.'%');                      
                      })->orWhere('start_date','LIKE','%'.$queryString.'%')
                    ->orWhere('end_date','LIKE','%'.$queryString.'%')
                    ->where('active','1')
                    ->get();
               
        }elseif(!empty($queryString)){
            
            $licences = TemporalLicence::with('company','people')
                            ->where('liquor_licence_number','like','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('people', function($query) use($queryString){
                                $query->where('full_name', 'like', '%'.$queryString.'%');                                      
                            })->get();
        
        }elseif(!empty($queryString) && $request->withThrashed != '' && $request->active_status == 'Inactive'){
                    $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                                
                            })->orWhereHas('people', function($query) use($queryString){
                                $query->where('full_name', 'like', '%'.$queryString.'%');                                      
                            })
                            ->orWhere('trading_name','LIKE','%'.$queryString.'%')
                            ->where('active','!=','1')
                            ->withTrashed()
                            ->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->get();

        }else{
            $licences = TemporalLicence::whereNull('id')->get();
        }
        
        return Inertia::render('TemporalLicences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

    public function store(Request $request){
       $request->validate([
           'liquor_licence_number' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           'belongs_to' => 'required|in:Person,Company'
           ]);
           if(is_null($request->person)){
            $request->validate(['company' => 'required']);
            $temp = TemporalLicence::create([
                'company_id' => $request->company,
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'slug' => sha1(time()),
                ]);

           }else{
            $request->validate(['person' => 'required|exists:people,id']);
            $temp = TemporalLicence::create([
                'people_id' => $request->person,
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'slug' => sha1(time()),
                ]);

           }
           
           if($temp){
              return to_route('view_temp_licence',['slug' => $temp->slug])->with('success','Temporal Licence issued successfully.');
           }
           return back()->with('error','AN unknown error occured while creating temporal Licence.');
    }

    public function show($slug){



// $companies = Company::pluck('name','id');
// $people = People::pluck('full_name','id');
$licence = TemporalLicence::with('company','people')->whereSlug($slug)->first();


    $client_invoiced = TemporalLicenceDocument::where('doc_type','Client Invoiced')->where('temporal_licence_id',$licence->id)->first();
    $client_quoted = TemporalLicenceDocument::where('doc_type','Client Quoted')->where('temporal_licence_id',$licence->id)->first();
    $collate = TemporalLicenceDocument::where('doc_type','Collate Documents')->where('temporal_licence_id',$licence->id)->first();
    $liqour_board = TemporalLicenceDocument::where('doc_type','Payment To The Liquor Board')->where('temporal_licence_id',$licence->id)->first();
    $transfer_logded = TemporalLicenceDocument::where('doc_type','Transfer Lodged')->where('temporal_licence_id',$licence->id)->first();
    $licence_issued = TemporalLicenceDocument::where('doc_type','Licence Issued')->where('temporal_licence_id',$licence->id)->first();
    $licence_delivered = TemporalLicenceDocument::where('doc_type','Licence Delivered')->where('temporal_licence_id',$licence->id)->first();
    $tasks = Task::where('model_type','Temporal Licence')->where('model_id',$licence->id)->whereUserId(auth()->id())->get();
    

    if(is_null($licence->people_id)){

        //company documents
    $company_application_form = TemporalLicenceDocument::where('doc_type','Application Form')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();  
    $company_poa = TemporalLicenceDocument::where('doc_type','POA And RES')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_annexure_b = TemporalLicenceDocument::where('doc_type','Annexure B')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_annexure_c = TemporalLicenceDocument::where('doc_type','Annexure C')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_cipc = TemporalLicenceDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_id_document = TemporalLicenceDocument::where('doc_type','ID Document')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_representations = TemporalLicenceDocument::where('doc_type','Representations')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_landlord_letter = TemporalLicenceDocument::where('doc_type','Landlord Letter')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_security_letter = TemporalLicenceDocument::where('doc_type','Security Letter')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_advert =  TemporalLicenceDocument::where('doc_type','Advert/Blurb')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
    $company_plan = TemporalLicenceDocument::where('doc_type','Plan/Maps')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();


    return Inertia::render('TemporalLicences/ViewTemporalLicence',
        ['licence' => $licence,
        // 'people' => $people,
        // 'companies' => $companies,
         'client_invoiced' => $client_invoiced,
         'client_quoted' => $client_quoted,
         'collate' => $collate,
         'liqour_board' => $liqour_board,
         'transfer_logded' => $transfer_logded,
         'licence_issued' => $licence_issued,
         'licence_delivered' => $licence_delivered,
         'tasks' => $tasks,
         'company_application_form' => $company_application_form,
         'company_poa' => $company_poa,
         'company_annexure_b' => $company_annexure_b,
         'company_annexure_c' => $company_annexure_c,
         'company_cipc' => $company_cipc,
         'company_id_document' => $company_id_document,
         'company_representations' => $company_representations,
         'company_landlord_letter' => $company_landlord_letter,
         'company_security_letter' => $company_security_letter,
         'company_advert' => $company_advert,
         'company_plan' => $company_plan
        ]);

    }else{

        $get_person_id_document = PeopleDocument::where('people_id',$licence->people_id)->where('doc_type','ID Document')->first();
            //individual documents
        $individual_application_form = TemporalLicenceDocument::where('doc_type','Application Form')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();  
        $power_of_attorney = TemporalLicenceDocument::where('doc_type','Power Of Attorney')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_annexure_b = TemporalLicenceDocument::where('doc_type','Annexure B')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_annexure_c = TemporalLicenceDocument::where('doc_type','Annexure C')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_representations = TemporalLicenceDocument::where('doc_type','Representations')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_landlord_letter = TemporalLicenceDocument::where('doc_type','Landlord Letter')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_security_letter = TemporalLicenceDocument::where('doc_type','Security Letter')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_advert =  TemporalLicenceDocument::where('doc_type','Advert/Blurb')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
        $individual_plan = TemporalLicenceDocument::where('doc_type','Plan/Maps')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();

        return Inertia::render('TemporalLicences/ViewTemporalLicence',
        [
         
        'licence' => $licence,
        'client_invoiced' => $client_invoiced,
        'client_quoted' => $client_quoted,
        'collate' => $collate,
        'liqour_board' => $liqour_board,
        'transfer_logded' => $transfer_logded,
        'licence_issued' => $licence_issued,
        'licence_delivered' => $licence_delivered,
        'tasks' => $tasks,
        'individual_application_form' => $individual_application_form,
        'power_of_attorney' => $power_of_attorney,
        'individual_annexure_b' => $individual_annexure_b,
        'individual_annexure_c' => $individual_annexure_c,
        'individual_representations' => $individual_representations,
        'individual_landlord_letter' => $individual_landlord_letter,
        'individual_security_letter' => $individual_security_letter,
        'individual_advert' => $individual_advert,
        'individual_plan' => $individual_plan,
        'get_person_id_document' => $get_person_id_document
        ]);
    }
    

    }


    public function update(Request $request,$slug){
        $temp = TemporalLicence::whereSlug($slug)->first();
        if(!is_null($temp->status) && empty($request->status)){
            $db_status = $temp->status;
            $status = $db_status;
        }elseif(!empty($request->status)){
            $sorted_statuses = Arr::sort($request->status);
            $status = last($sorted_statuses);
        }
            $temp->update([
                'company_id' => $request->company,
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'client_paid_at' => $request->client_paid_at,
                'payment_to_liquor_board_at' => $request->payment_to_liquor_board_at,
                'logded_at' => $request->logded_at,
                'issued_at' => $request->issued_at,
                'delivered_at' => $request->delivered_at,
                "status" => $status,
                ]);
    
              
            if($temp){
               return back()->with('success','Temporal Licence updated successfully.');
            }
            return back()->with('error','An unknown error occured while updating temporal Licence.');
     }

     public function destroy($slug){
        try {
            $licence = TemporalLicence::whereSlug($slug);
            if($licence->delete()){
               return to_route('temp_licences')->with('success','Temporal Licence deleted successfully.');
            }
            
        } catch (\Throwable $th) {
            return to_route('temp_licences')->with('error','AN unknown error occured while deleteing temporal Licence.');
        }

     }
}
