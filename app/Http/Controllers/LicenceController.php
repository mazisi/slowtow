<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LicenceController extends Controller
{
    public function index()
    {
        $licences = Licence::with('company')->latest()->get();
        return Inertia::render('Licence',['licences' => $licences]);
    }

    public function create($slug)
    {
        $company = Company::whereSlug($slug)->firstOrFail();
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('CreateLicence',['licence_dropdowns' => $licence_dropdowns,
        'company' => $company]);
    }

    public function store(Request $request,$slug){
       
        $request->validate([
            "trading_name" => "required",
            "licence_type" => "required",
            "licence_date" => "required",
        ]);
        $get_comp = Company::whereSlug($slug)->first();
        Licence::create([
            "trading_name" => $request->trading_name,
            "licence_type" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "company_id"   => $get_comp->id,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "licence_expire_date" => $request->licence_expire_date,
            "file_number" => $request->file_number,
            "account_number" => $request->account_number,
            "address" => $request->address,
            "province" => $request->province,
            "consultant_name" => $request->consultant_name,
            "must_renew" => $request->must_renew,
            "licence_status" => $request->is_licence_active,
            'slug' => Str::replace(' ','_',$request->trading_name).sha1(time()),
        ]);
        return redirect(route('licences'))->with('success','Licence created successfully.');
    }

    public function show($slug)
    {
        $licence = Licence::with('company')->whereSlug($slug)->firstOrFail();
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('ViewLicence',['licence' => $licence,'licence_dropdowns' => $licence_dropdowns]);
    }
}
