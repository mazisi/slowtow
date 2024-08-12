<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Licence;
use App\Models\LicenceRenewal;
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
        ->when(request('year') && request('type') == 'New-Apps', function ($query) {
            $query->when(request('year'), function ($query) {
                $query->whereYear('created_at', request('year'));
            })
            ->when(request('province') && request('type') == 'New-Apps', function ($query) {
                $query->where('province', request('province'));
            });
        })
        ->where('is_new_app', 1)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month');
    
        // Initialize an array with all months set to 0
        $monthlyLicenceCounts = array_fill(1, 12, 0);
        
        // Fill the monthly counts with actual data
        foreach ($licencesByMonth as $month => $count) {
            $monthlyLicenceCounts[$month] = $count;
        }
        //dd( $monthlyLicenceCounts,$licencesByMonth);
        return $monthlyLicenceCounts;
    }
    
    

    function renewals() {  
        $year = request('year') ? request('year') : Carbon::now()->year;
        $type = request('type');

        $renewalsByMonth = LicenceRenewal::select(
            DB::raw('MONTH(licences.licence_date) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->join('licences', 'licences.id', '=', 'licence_renewals.licence_id')
        ->when(request('year') && $type == 'renewals', function ($query) use ($year) {
            $query->where('date', $year);
        })
        ->when(request('province') && $type == 'renewals', function ($query) {
            $query->whereHas('licence', function ($query) {
                $query->where('province', request('province'));
            });
        })
        ->groupBy(DB::raw('MONTH(licences.licence_date)'))
        ->pluck('count', 'month');
    
        // Initialize an array with all months set to 0
        $monthlyRenewalCounts = array_fill(1, 12, 0);
    
        // Fill the monthly counts with actual data
        foreach ($renewalsByMonth as $month => $count) {
            $monthlyRenewalCounts[$month] = $count;
        }
  
        
    
        return $monthlyRenewalCounts;
    }



function tempLicences() { 
    $currentYear = now()->year; 
    $year = request('year') ? request('year') : $currentYear;
    $type = request('type');

    
        $tempLicences = TemporalLicence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when(request('year') && $type == 'temps', function ($query) use ($year) {
            $query->whereYear('created_at', $year);
        })
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month');
    
        // Initialize an array with all months set to 0
        $monthlyTempLicenceCounts = array_fill(1, 12, 0);
    
        // Fill the monthly counts with actual data
        foreach ($tempLicences as $month => $count) {
            $monthlyTempLicenceCounts[$month] = $count;
        }
    

    

    return $monthlyTempLicenceCounts;
}

}