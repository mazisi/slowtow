<?php

namespace App\Http\Controllers;

use App\Actions\LicenceFilterAction;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Events\LogUserActivity;
use App\Models\LicenceDocument;

class LicenceController extends Controller
{
    /**
     * Display a list of licences with filtering options.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $licences = (new LicenceFilterAction)->filterLicence();
        $all_licence_types = LicenceType::get();

        return Inertia::render('Licences/Licence', [
            'licences' => $licences,
            'all_licence_types' => $all_licence_types
        ]);
    }

    /**
     * Render the form for creating a new licence with variations (company or individual).
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $get_reg_num_or_id_number = '';

        if (request('variation') && request('variation') == 'Company') {
            $comp = Company::whereId(request('id'))->first(['reg_number']);
            $get_reg_num_or_id_number = $comp->reg_number;
        } elseif (request('variation') && request('variation') == 'Individual') {
            $person = People::whereId(request('id'))->first(['id_or_passport']);
            $get_reg_num_or_id_number = $person->id_or_passport;
        }

        $companies = Company::pluck('name', 'id');
        $people = People::pluck('full_name', 'id');
        $licence_dropdowns = LicenceType::get();
        $wholesaleLicenceTypes = LicenceType::where('province', 'null')->get();

        return Inertia::render('Licences/CreateLicence', [
            'licence_dropdowns' => $licence_dropdowns,
            'wholesaleLicenceTypes' => $wholesaleLicenceTypes,
            'companies' => $companies,
            'people' => $people,
            'type' => request('type'),
            'get_reg_num_or_id_number' => $get_reg_num_or_id_number
        ]);
    }

    /**
     * Update the active status of a licence based on the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateActiveStatus(Request $request, $slug)
    {
        $lic = Licence::whereSlug($slug)->first();

        if ($request->unChecked) {
            $lic->update(['is_licence_active' => 0]);
        } else {
            $lic->update(['is_licence_active' => $request->status]);
        }

        return back()->with('success', 'Saved.');
    }

    /**
     * Store a new licence with validation and logging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'belongs_to' => 'required|in:Company,Individual',
            'trading_name' => 'required',
            'licence_type' => 'required',
            'type' => 'required|in:retail,wholesale',
            'province' => 'required',
            'licence_number' => 'required|unique:licences,licence_number'
        ]);

        try {
            $licence = Licence::create([
                'type' => $request->type,
                'is_licence_active' => $request->is_licence_active,
                'trading_name' => $request->trading_name,
                'belongs_to' => $request->belongs_to,
                'licence_type_id' => $request->licence_type,
                'company_id' => $request->company,
                'people_id' => $request->person,
                'licence_number' => $request->licence_number,
                'old_licence_number' => $request->old_licence_number,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'board_region' => $request->board_region,
                'postal_code' => $request->postal_code,
                'slug' => sha1(time())
            ]);

            $activity = 'Licence created By: ' . $licence->trading_name . ', ' . $licence->licence_number;
            event(new LogUserActivity(auth()->user(), $activity));

            return redirect()->route('view_licence', ['slug' => $licence->slug])
                ->with('success', 'Licence created successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error creating Licence');
        }
    }

    /**
     * Show the details of a licence with related documents and tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $licence = Licence::with('company', 'people', 'licence_documents')
            ->whereSlug($request->slug)
            ->first();

        $original_lic = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Original-Licence')
            ->latest()
            ->first();

        $licence_issued = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Licence Issued')
            ->latest()
            ->first();

        $duplicate_original_lic = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Duplicate-Licence')
            ->latest()
            ->first();

        $original_lic_delivered = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Original-Licence-Delivered')
            ->latest()
            ->first();

        $licence_delivered = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Licence Delivered')
            ->latest()
            ->first();

        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Duplicate-Original-Licence-Delivered')
            ->latest()
            ->first();

        $companies = Company::pluck('name', 'id');
        $people = People::pluck('full_name', 'id');
        $licence_dropdowns = LicenceType::orderBy('licence_type')->get();
        $tasks = Task::where('model_type', 'Licence')
            ->where('model_id', $licence->id)
            ->latest()
            ->paginate(4)
            ->withQueryString();
            

        $view = $licence->is_new_app ? 'ViewNewApp' : 'ViewLicence';

        return Inertia::render('Licences/' . $view, [
            'licence' => $licence,
            'licence_dropdowns' => $licence_dropdowns,
            'tasks' => $tasks,
            'companies' => $companies,
            'people' => $people,
            'original_lic' => $original_lic,
            'licence_issued' => $licence_issued,
            'duplicate_original_lic' => $duplicate_original_lic,
            'original_lic_delivered' => $original_lic_delivered,
            'licence_delivered' => $licence_delivered,
            'duplicate_original_lic_delivered' => $duplicate_original_lic_delivered
        ]);
    }

    /**
     * Update the details of a licence with validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $slug)
    {
        try {
            $request->validate([
                'change_company' => 'required|exists:companies,id'
            ]);

            $company_var = $request->change_company;

            if (empty($request->change_company)) {
                $company_var = $request->company_id;
            }

            $update = Licence::whereSlug($slug)->update([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->company_id,
                'people_id' => $request->person_id,
                'licence_number' => $request->licence_number,
                'old_licence_number' => $request->old_licence_number,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'board_region' => $request->board_region,
                'postal_code' => $request->postal_code,
                'company_id' => $company_var,
                'renewal_amount' => $request->renewal_amount,
                'latest_renewal' => $request->latest_renewal
            ]);

            if ($update) {
                return back()->with('success', 'Licence updated successfully.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Error updating licence.');
        }
    }

    /**
     * Delete a licence with logging.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($slug)
    {
        $licence = Licence::whereSlug($slug)->first();
        $activity = 'Deleted Licence By: ' . $licence->trading_name . ', ' . $licence->licence_number;
        event(new LogUserActivity(auth()->user(), $activity));

        if ($licence->delete()) {
            return redirect()->route('licences')->with('success', 'Licences deleted successfully.');
        }

        return redirect()->route('licences')->with('error', 'Error deleting licence.');
    }
}
