<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use Illuminate\Support\Facades\DB;

class SlotowDashboardController extends Controller
{
    public function index(){
        $licences = Licence::count();
        $companies = Company::count();
        $people = People::count();
        $temp_licences = TemporalLicence::count();

        // $count_group_licences = DB::table('licences')
        //                         ->select(DB::raw('MONTH(licence_date) as month, count(*) as number'))
        //                         ->whereNotNull('licence_date')
        //                         ->groupByRaw('MONTH(licence_date)')
        //                         ->orderByRaw('MONTH(licence_date) asc')
        //                         ->get();

                              


        return Inertia::render('Dashboard',
    [
        'licences' => $licences,
        'companies' => $companies,
        'people' => $people,
        'temp_licences' => $temp_licences
        ]);
    }
}
