<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceRenewal;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use Illuminate\Support\Facades\DB;

class SlotowDashboardController extends Controller
{
    public function index(){
        $years = DB::table('years')->orderBy('year', 'DESC')->get()->pluck('year');

        return Inertia::render('Dashboard',[
        'licences' => $this->newLicences(),
        'renewals' => $this->renewals(),
        'years' => $years,
        'tempLicences' => $this->tempLicences(),
        ]);
    }

    function newLicences() {        
        $licencesByMonth = Licence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month');
        
        // Initialize an array with all months set to 0
        $monthlyLicenceCounts = array_fill(1, 12, 0);
        
        // Fill the monthly counts with actual data
        foreach ($licencesByMonth as $month => $count) {
            $monthlyLicenceCounts[$month] = $count;
        }
        return $monthlyLicenceCounts;
    }

    function renewals() {        
        $renewalsByMonth = LicenceRenewal::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month');
        
        // Initialize an array with all months set to 0
        $monthlyRenewalCounts = array_fill(1, 12, 0);
        
        // Fill the monthly counts with actual data
        foreach ($renewalsByMonth as $month => $count) {
            $monthlyLicenceCounts[$month] = $count;
        }
        return $monthlyRenewalCounts;
    }

    function tempLicences() {        
        $tempLicencesByMonth = LicenceRenewal::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month');
        
        // Initialize an array with all months set to 0
        $monthlyTempLicenceCounts = array_fill(1, 12, 0);
        
        // Fill the monthly counts with actual data
        foreach ($tempLicencesByMonth as $month => $count) {
            $monthlyLicenceCounts[$month] = $count;
        }
        return $monthlyTempLicenceCounts;
    }
}
