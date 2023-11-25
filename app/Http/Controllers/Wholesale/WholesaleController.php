<?php

namespace App\Http\Controllers\Wholesale;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WholesaleController extends Controller
{
    public function show(Request $request){
        $licence = Licence::with('company','licence_stage_dates','documents')->whereSlug($request->slug)->first();
      
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString(); 

        return Inertia::render('Wholesale/ViewWholesale',[
            'licence' => $licence,
            'tasks' => $tasks]);
    }
}
