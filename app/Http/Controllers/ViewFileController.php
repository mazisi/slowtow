<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;
use App\Models\LicenceDocument;
use App\Models\TransferDocument;
use App\Models\NominationDocument;

class ViewFileController extends Controller
{
    public function view_file($model, $model_id){
        switch ($model) {
            case 'PeopleDocument':
                $people_doc = PeopleDocument::whereId($model_id)->first(['path']);               
                    
                if($people_doc){
                    if(fileExist(env('AZURE_STORAGE_URL').'/'.$people_doc->path)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$people_doc->path);
                    }
                    return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;


             case 'LicenceDocument':
                    $lic_doc = LicenceDocument::whereId($model_id)->first(['document_file']);               
                        
                    if($lic_doc){
                        if(fileExist(env('AZURE_STORAGE_URL').'/'.$lic_doc->document_file)){
                            return Inertia::location(env('AZURE_STORAGE_URL').'/'.$lic_doc->document_file);
                        }
                        return Inertia::render('ErrorPages/FileNotFound');
                    }
                    return back()->with('error', 'Fatal Error. Please contact administrator.');
                    break;

            case 'TransferDocument':
                $transfer_doc = TransferDocument::whereId($model_id)->first(['document']);               
                    
                if($transfer_doc){
                    if(fileExist(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document);
                    }
                    return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'NominationDocument':
                $transfer_doc = NominationDocument::whereId($model_id)->first(['document']);               
                    
                if($transfer_doc){
                    if(fileExist(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document);
                    }
                    return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;
            
            default:
                # code...
                break;
        }
         
     }
}
