<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\DuplicateOriginal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DuplicateOriginalDoc;

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
    
    function uploadDocument(Request $request) {
        try {
                $request->validate([
                    'licence_id' => 'required|exists:duplicate_originals,id',
                    'document_file' => 'required',
                    'doc_type' => 'required'
                ]);
                
                $removeSpace = str_replace(' ', '_',$request->document_file->getClientOriginalName());
                $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
                $request->file('document_file')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

                if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
                        $fileModel = new DuplicateOriginalDoc;
                        $fileModel->document_name = $request->document_file->getClientOriginalName();
                        $fileModel->duplicate_original_id = $request->licence_id;
                        $fileModel->doc_type = $request->doc_type;
                        $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;        
                        if($fileModel->save()){
                            //Now update the status of the duplicate original
                            $duplicateOriginal = DuplicateOriginal::whereId($request->licence_id)->first();
                            $duplicateOriginal->update(['status' => $request->stage]);
                            if($request->doc_type == 'Duplicate Original Issued' || $request->doc_type == 'Duplicate-Original-Licence-Delivered'){
                               $this->storeLicenceDocs($request);
                            }
                        }

                }else{
                    return back()->with('error','Azure storage could not be reached.Please try again.');
                  }
            
            return back()->with('success', 'Document uploaded successfully');
        } catch (\Throwable $th) {
             throw $th;
            return back()->with('error', 'An error occurred while uploading.');
        }
        
    }

    function storeLicencedocs($request) {
        
            $doc_type = $request->doc_type == 'Duplicate Original Issued' ? 'Duplicate-Licence' : 'Duplicate-Original-Licence-Delivered';

            $get_licence = DuplicateOriginal::whereId($request->licence_id)->first();
            $fileModel = new LicenceDocument;
            $fileModel->document_name = $request->document_file->getClientOriginalName();
            $fileModel->licence_id = $get_licence->licence_id;
            $fileModel->document_type = $doc_type;
            $fileModel->num = $request->num ? $request->num : null;
            $fileModel->document_file = env('AZURE_STORAGE_CONTAINER') . '/' . $this->generateFileName($request->document_file);
            $fileModel->save();
        
        
    }

    private function generateFileName($documentFile)
    {
        $removeSpace = str_replace(' ', '_', $documentFile->getClientOriginalName());
        $fileName = Str::limit(sha1(now()), 3) . str_replace('-', '_', $removeSpace);

        return $fileName;
    }

    public function updateDate(Request $request){
        $request->validate([
         'licence_id' => 'required|exists:duplicate_originals,id',
         'dated_at' => 'required',
         'stage' => 'required'
        ]);
        try {
            $dup = DuplicateOriginal::whereId($request->licence_id)->first();
         switch ($request->stage) {
             case 'Client Paid':
                 $db_column = 'paid_at';
                 break;
             case 'Application Lodged':
                 $db_column = 'lodged_at';
                 break;
             case 'Duplicate Original Issued':
                 $db_column = 'issued_at';
                 break;
             case 'Duplicate Original Delivered':
                 $db_column = 'delivered_at';
                 break; 
            case 'Payment To The Liquor Board':
            $db_column = 'liquor_board_at';
            break;             
             default:
                 return back()->with('error','Sorry..An error occured.');
                 break;
         }
 
        $dup->update([
         $db_column => $request->dated_at,
         
        ]);
        if($dup){
         return back()->with('success','Date updated successfully.');
        }
        return back()->with('error','Sorry..An error occured.');
        } catch (\Throwable $th) {
            throw $th;
        }
     }

    //Delete document
    function deleteDocument($id,$prevStage) {
        try {
            $document = DuplicateOriginalDoc::findOrFail($id);
            $document->duplicate_original->update(['status' => $prevStage]);
            $document->delete();
            return back()->with('success', 'Document deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred while deleting the document.');
        }
    }

    function destroy($slug) {
        try {
            DuplicateOriginal::whereSLug($slug)->delete();
        return back()->with('success', 'Duplicate original deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred while deleting the duplicate original.');
        }
    }

    public function abandon($slug){
        $lic = DuplicateOriginal::whereSlug($slug)->first();
        $abandoned = Task::where('model_id', $lic->id)->where('body', 'THIS DUPLICATE ORIGINAL HAS BEEN ABANDONED.')->first();
        if($abandoned){
            return back()->with('error', 'This duplicate original has already been marked as abandoned.');
        }
        Task::create([
            'user_id' => auth()->id(),
            'model_type'=> 'Duplicate-Originals',
            'model_id' => $lic->id,
            'body' => 'THIS DUPLICATE ORIGINAL HAS BEEN ABANDONED.',
            'is_abandoned' => 1
        ]);        

        return back()->with('success', 'Saved.');
    }
}