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
        $licencesByMonth = Licence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        // ->whereIn(DB::raw('MONTH(licence_date)'), $request->month)
        ->when(request('year'), function ($query) {
            $query->whereYear('created_at', request('year'));
        })
        ->when(request('month'), function ($query) {
            $query->whereMonth('created_at', request('month'));
        })
        ->when(request('province'), function ($query) {
            $query->where('province', request('province'));
        })
        ->groupBy(DB::raw('MONTH(created_at)'))
        //

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
        
        ->when(request('year'), function ($query) {
            $query->whereYear('created_at', request('year'));
        })
        ->when(request('month'), function ($query) {
            $query->whereMonth('created_at', request('month'));
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
        $tempLicences = TemporalLicence::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        
        ->when(request('year'), function ($query) {
            $query->whereYear('created_at', request('year'));
        })
        ->when(request('month'), function ($query) {
            $query->whereMonth('created_at', request('month'));
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
