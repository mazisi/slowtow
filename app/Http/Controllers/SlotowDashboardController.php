<?php

namespace App\Http\Controllers;

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
        $currentYear = now()->year;  
        $defaultProvince = 'Gauteng';

        $licencesByMonth = Licence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when(request('type') == 'New-Apps', function ($query) {
            $query->when(request('year'), function ($query) {
                $query->whereYear('created_at', request('year'));
            })
            ->when(request('province'), function ($query) {
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
    
        return $monthlyLicenceCounts;
    }
    
    

    function renewals() {  
       
        $currentYear = now()->year; 
        $defaultProvince = 'Gauteng';
        
        $renewalsByMonth = LicenceRenewal::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when(request('year'), function ($query) {
            $query->where('date', request('year'));
        })
        ->when(request('province'), function ($query) {
            $query->whereHas('licence', function ($query) {
                $query->where('province', request('province'));
            });
        })
        ->groupBy(DB::raw('MONTH(created_at)'))
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
        
        $tempLicences = TemporalLicence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when(request('year'), function ($query, $year) {
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