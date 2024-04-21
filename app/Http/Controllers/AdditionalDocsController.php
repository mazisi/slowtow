<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AdditionalDoc;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;

class AdditionalDocsController extends Controller
{
    function store(Request $request) {
        
        $request->validate([
            'description' => 'required',
            // 'document' => 'mimes:pdf',
            'uploaded_at' => 'required',
            "modelable_id" => "required",
            "modelable_type" => "required",
            ]);

if($request->hasFile('document')){$this->handleDocUpload($request); return;}
    $model = "";
    switch ($request->modelable_type) {
        case 'Licence':
            $model = Licence::where('id', $request->modelable_id )->first();
            break;
        case 'Alteration':
            $model = Alteration::where('id', $request->modelable_id )->first();
            break;
        case 'LicenceRenewal':
            $model = LicenceRenewal::where('id', $request->modelable_id )->first();
            break;
        case 'LicenceTransfer':
            $model = LicenceTransfer::where('id', $request->modelable_id )->first();
            break;
        default:
            # code...
            break;
    }
      $model->additionalDocs()->create([
            'modelable_id' => $request->modelable_id,
            'modelable_type' => 'App\Models\''.$request->modelable_type,
            'description' => $request->description,
            'uploaded_at' => $request->uploaded_at,
        ]);
    
    return back()->with('success','Saved successfully.');
}

function handleDocUpload(Request $request) {
    try {
        $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
    $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace); 
    $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){

    $model = "";
    switch ($request->modelable_type) {
        case 'Licence':
            $model = Licence::where('id', $request->modelable_id )->first();
            break;
        case 'Alteration':
            $model = Alteration::where('id', $request->modelable_id )->first();
            break;
        case 'LicenceRenewal':
            $model = LicenceRenewal::where('id', $request->modelable_id )->first();
            break;
        case 'LicenceTransfer':
            $model = LicenceTransfer::where('id', $request->modelable_id )->first();
            break;
        default:
            # code...
            break;
    }
   
      $model->additionalDocs()->create([
            'modelable_id' => $request->modelable_id,
            'modelable_type' => 'App\Models\''.$request->modelable_type,
            'description' => $request->description,
            'uploaded_at' => $request->uploaded_at,
            'document_name' => $removeSpace,
            'path' => env('AZURE_STORAGE_CONTAINER').'/'.$fileName,
        ]);

            }else{
            return back()->with('error','Azure storage could not be reached.Please try again.');
            }

    } catch (\Throwable $th) {
        throw $th;
        return back()->with('error','Error uploading document.');
    }

}

    function destroy($id) {
        try {
            AdditionalDoc::find($id)->delete();
            return back()->with('success','Document deleted successfully.');
        } catch (\Throwable $th) {
            throw $th;
            return back()->with('error','Error deleting document.');
        }
        

    }
}
