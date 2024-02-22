<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;
use App\Http\Controllers\Controller;

class AlterationController extends Controller
{
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->first(['id','trading_name','slug']);
        $alterations = Alteration::where('licence_id',$licence->id)->latest()->paginate(10)->withQueryString();
        return Inertia::render('CompanyAdmin/Alterations/Alteration',['licence'=> $licence,'alterations' => $alterations]);
      }

      public function show($slug){
        $alteration = Alteration::with('licence','documents','dates')->whereSlug($slug)->first();     
        return Inertia::render('CompanyAdmin/Alterations/ViewMyAlterations',[
          'alteration' => $alteration]);
      }
}
