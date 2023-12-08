<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DuplicateOriginal;
use App\Models\Task;

class DuplicateOriginalsController extends Controller
{
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $originals_years = DuplicateOriginal::where('licence_id',$licence->id)->orderBy('year','DESC')->paginate(10)->withQueryString();
        $years = DB::table('years')->get()->pluck('year');//dropdown of years
     
        return Inertia::render('DuplicateOriginals/DuplicateOriginals',[
            'licence' => $licence,
            'originals_years' => $originals_years,
            'years' => $years
          ]);
       
    }
    public function view_duplicate($slug){ 
      $duplicate_original = DuplicateOriginal::with('licence','duplicate_documents')->whereSlug($slug)->first();

      $tasks = Task::where('model_type', 'Duplicate-Originals')
            ->where('model_id', $duplicate_original->id)
            ->latest()
            ->paginate(4)
            ->withQueryString();

          return Inertia::render('DuplicateOriginals/ViewDuplicateOriginal',[
             'duplicate_original' => $duplicate_original,
             'tasks'  => $tasks
            ]);
    }

    //store duplicate originals
    public function store(Request $request){
        $request->validate([
            'year' => 'required',
            'licence_id' => 'required|exists:licences,id'            
        ]);
       
        $dup = DuplicateOriginal::create([
            'year' => $request->year,
            'licence_id' => $request->licence_id,
            'slug' => sha1(time())
        ]);
        return to_route('view_duplicate_original',['slug' => $dup->slug]);
    }

    function updateStage(Request $request) {
        try {
            $orig = DuplicateOriginal::whereSlug($request->slug)->first(['id', 'status']);
            $status = '';

            if ($request->status) {
                if ($request->unChecked) {
                    $status = $request->prevStage;
                } else {
                    $status = $request->status[0];
                }
                $orig->update([
                    'status' => $status,
                ]);
            }
            return back()->with('success', 'Status updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred while updating.');
        }
    }
    
}