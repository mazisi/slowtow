<?php

namespace App\Http\Controllers;

use App\Models\LicenceRenewal;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $renewals = LicenceRenewal::with(['licence','renewal_documents' => function ($query){
            $query ->where('doc_type','Client Quoted');            
          }])->paginate(2);
        return Inertia::render('Report',['renewals' => $renewals]);
    }

}
