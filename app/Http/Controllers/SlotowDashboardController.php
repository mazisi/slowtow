<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Licence;
use App\Models\People;
use App\Models\TemporalLicence;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SlotowDashboardController extends Controller
{
    public function index(){
        $licences = Licence::count();
        $companies = Company::count();
        $people = People::count();
        $temp_licences = TemporalLicence::count();
        return Inertia::render('Dashboard',
    [
        'licences' => $licences,
        'companies' => $companies,
        'people' => $people,
        'temp_licences' => $temp_licences
    ]);
    }
}
