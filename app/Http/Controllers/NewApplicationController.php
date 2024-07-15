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
    /**
     * The registration number or ID number of a company or individual.
     *
     * @var string
     */
    public $get_reg_num_or_id_number = '';

    /**
     * Retrieves data needed to render the new license application form and returns the rendered view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        if (request('variation') === 'Company') {
            $comp = Company::whereId(request('id'))->first(['reg_number']);
            $this->get_reg_num_or_id_number = $comp->reg_number;
        } elseif (request('variation') === 'Individual') {
            $person = People::whereId(request('id'))->first(['id_or_passport']);
            $this->get_reg_num_or_id_number = $person->id_or_passport;
        }

        $persons = People::pluck('full_name', 'id');
        $companies = Company::pluck('name', 'id');
        $licence_dropdowns = LicenceType::orderBy('licence_type', 'ASC')->get();
        $wholesaleLicenceTypes = LicenceType::where('province', 'null')->get();


        return Inertia::render('New Applications/Index', [
            'persons' => $persons,
            'companies' => $companies,
            'licence_dropdowns' => $licence_dropdowns,
            'wholesaleLicenceTypes' => $wholesaleLicenceTypes,
            'type' => request('type'),
            'get_reg_num_or_id_number' => $this->get_reg_num_or_id_number
        ]);
    }

    /**
     * Validates and stores a new licence application based on the provided request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'trading_name' => 'required',
                'licence_type' => 'required|exists:licence_types,id',
                'belongs_to' => 'required|in:Company,Individual',
                'board_region' => ''
            ]);

            if ($request->belongs_to === 'Company') {
                $request->validate(['company' => 'required|exists:companies,id']);
            } else {
                $request->validate(['person' => 'required|exists:people,id']);
            }

            $licence = Licence::create([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'licence_number' => $request->licence_number,
                'type' => $request->type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->belongs_to === 'Company' ? $request->company : null,
                'people_id' => $request->belongs_to === 'Individual' ? $request->person : null,
                'board_region' => $request->board_region,
                'is_new_app' => true,
                'import_export' => $request->import_export,
                'address' => $request->address,
                'address2' => $request->address2,
                'is_licence_active' => true,
                'coordinates' => $request->coordinates,
                'address3' => $request->address3,
                'province' => $request->province,
                'slug' => sha1(now())
            ]);

            if ($licence && $request->type === 'retail') {
                return redirect()->route('view_registration', ['slug' => $licence->slug])->with('success', 'New App created successfully.');
            }

            return redirect()->route('view_wholesale', ['slug' => $licence->slug])->with('success', 'New App created successfully.');
            
        } catch (\Throwable $th) {throw $th;
           // return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Validates and updates an existing license application based on the provided request data and slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $slug)
    {
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
                'import_export' => $request->import_export,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'licence_number' => $request->licence_number,
                'client_number' => $request->client_number,
                'latest_renewal' => $request->latest_renewal,
                'coordinates' => $request->coordinates,
                'licence_date' => $request->licence_date,
                'licence_issued_at' => $request->licence_date,
                'postal_code' => $request->postal_code,
            ]);

            if ($licence) {
                return back()->with('success', 'Licence updated successfully.');
            }
        } catch (\Throwable $th) {

            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Retrieves and returns the license registration information for a given slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function view_registration(Request $request)
    {
        $licence = Licence::with('company', 'licence_stage_dates', 'documents','additionalDocs')->whereSlug($request->slug)->first();
        $tasks = Task::where('model_type', 'Licence')->where('model_id', $licence->id)->latest()->paginate(4)->withQueryString();
        $view = $licence->type === 'wholesale' ? 'New Applications/Wholesale/ViewWholesale' : 'New Applications/Registration';
        return Inertia::render($view, [
            'licence' => $licence,
            'tasks' => $tasks
        ]);
    }


    function getRetailLicenceDate($licence, $request) {
        if ($licence->type == 'retail' && !$request->unChecked && $request->status[0] == 2300 && $request->stage == 'Licence Issued') {
            $licenceDate = $licence->licence_stage_dates()->where('stage', $request->stage)->first();
       
            if (is_null($licenceDate)) {
                return true;
            }
            return false;
        }
//licence delivered
        if ($licence->type == 'retail' && !$request->unChecked && $request->status[0] == 2400 && $request->stage == 'Licence Delivered') {
            $licenceDate = $licence->licence_stage_dates()->where('stage', $request->stage)->first();
       
            if (is_null($licenceDate)) {
                return true;
            }
            return false;
        }
    }


    function getWholesaleLicenceDate($licence, $request) {
        if ($licence->type == 'wholesale' && !$request->unChecked && $request->status[0] == 1100 && $request->stage == 'NLA 8 Issued') {
            $licenceDate = $licence->licence_stage_dates()->where('stage', $request->stage)->first();
       
            if (is_null($licenceDate)) {
                return true;
            }
            return false;
        }
//licence delivered
        if ($licence->type == 'wholesale' && !$request->unChecked && $request->status[0] == 1500 && $request->stage == 'Original Licence Delivered') {
            $licenceDate = $licence->licence_stage_dates()->where('stage', $request->stage)->first();
       
            if (is_null($licenceDate)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Updates the status of a licence registration based on the provided request data and slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRegistration(Request $request, $slug)
    {
        try {
            $licence = Licence::whereSlug($slug)->first();
            if($licence->type == 'wholesale'){
                if($this->getWholesaleLicenceDate($licence, $request)){
                    return back()->with('error', 'Update stage date first.');
                 }

            }else{
                if($this->getRetailLicenceDate($licence, $request)){
                   return back()->with('error', 'Update stage date first.');
                }
            }
            
            
            $status = '';

            if ($request->status) {
                if ($request->unChecked) {
                    $status = $request->prevStage;
                } else {
                    $status = $request->status[0];
                }
            }

            if ($status >= 2300 && $licence->type == 'retail' && !$request->unChecked) {
                $nom = Nomination::where('year', now()->format('Y'))->where('licence_id', $licence->id)->first();

                if (is_null($nom)) {
                    Nomination::create([
                        'licence_id' => $licence->id,
                        'year' => now()->format('Y'),
                        'slug' => sha1(now())
                    ]);
                }

                $licence->update(['is_new_app' => false]);

            }else if($status >= 1300 && $licence->type == 'wholesale' && !$request->unChecked){
                $nom = Nomination::where('year', now()->format('Y'))->where('licence_id', $licence->id)->first();

                if (is_null($nom)) {
                    Nomination::create([
                        'licence_id' => $licence->id,
                        'year' => now()->format('Y'),
                        'slug' => sha1(now())
                    ]);
                }

                $licence->update(['is_new_app' => false]);

            }

            $licence->update([
                'status' => $status <= 0 ? null : $status,
            ]);

            return back()->with('success', 'Status updated successfully');
        } catch (\Throwable $th) {
            throw $th;
            //return back()->with('error', 'An error occurred while updating.');
        }
    }

    /**
     * Validates and updates the date of a license registration based on the provided request data and slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRegistrationDate(Request $request, $slug)
    {
        try {
            $request->validate([
                'dated_at' => 'required',
                'stage' => 'required',
                'licence_id' => 'required|exists:licences,id'
            ]);
            //
            $licence = LicenceDate::where('licence_id',$request->licence_id)->where('stage',$request->stage)->first();
            if($licence){
                $licence->update(['dated_at' => $request->dated_at]);
                if($request->stage == 'NLA 9 Issued'){
                    $licence->licence->update(['licence_date' => $request->dated_at, 'is_new_app' => 0]);
                }
            }else{
                LicenceDate::create([
                    'dated_at' => $request->dated_at,
                    'licence_id' => $request->licence_id,
                    'stage' => $request->stage,
                ]);
            }



            $this->updateLicenceDate($request);

            return back()->with('success', 'Date updated successfully.');
        } catch (\Throwable $th) {throw $th;
            return back()->with('error', 'Error updating date.');
        }
    }

    /**
     * If request->stage is 'Licence Issued'(Retail) & 'NLA 8 Issued'(Wholsale) then its no longer a NewApp
     * Updates the `is_new_app` and `licence_date` fields of a `Licence` model based on the provided request data.
     *
     * @param object $request The request object that contains the data needed to update the licence date.
     *                        It should have the following properties:
     *                        - stage: The stage of the licence application.
     *                        - licence_id: The ID of the licence.
     *                        - date: The date to be updated.
     * @return void
     */
    function updateLicenceDate($request) {
        if ($request->stage === 'Licence Issued' || $request->stage === 'NLA 9 Issued') {
            Licence::whereId($request->licence_id)->update(['is_new_app' => false, 'licence_date' => $request->dated_at]);
        }
    }
}
