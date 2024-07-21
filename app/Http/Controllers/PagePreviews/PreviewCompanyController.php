<?php

namespace App\Http\Controllers\PagePreviews;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PreviewCompanyController extends Controller
{
    function preview($slug) {

        $html= view('pdfs.company', ['company' => Company::whereSlug($slug)->firstOrFail()])->render();
        // dd($html);
        $company = Company::whereSlug($slug)->with('licences.licence_documents')->firstOrFail();
        Pdf::loadHTML($html)->save(storage_path('app/public/').''.trim($company->name).'.pdf');
        $this->mergeDocuments($company);
        // return view('pdfs.company', ['company' => $company]);

        // return Inertia::render('PagePreviews/Company/CompanyPreview',
        // ['company' => $company]);
    }

    public function mergeDocuments($company)
{
    $merger = PDFMerger::init();
    try {
        $documents = [];
        $companyLicence = Licence::where('company_id', $company->id)->get();
        
        foreach ($companyLicence as $licence) {
            // Get all Duplicate original licences of all licences linked to the company if no original licence is available.
            // If the duplicate original has not yet been issued, attached the payment to the Liquor Board.
            
            $originals = LicenceDocument::where('licence_id', $licence->id)
                                        ->where('document_type', 'Original-Licence')
                                        ->get()
                                        ->toArray();
                                        
            $duplicates = LicenceDocument::where('licence_id', $licence->id)
                                         ->where('document_type', 'Duplicate-Licence')
                                         ->get()
                                         ->toArray();
            
            $documents = array_merge($documents, $originals, $duplicates);
        }
        
        Log::info('Total documents to merge: ' . count($documents));

        foreach ($documents as $doc) {
            $filePath = env('BLOB_FILE_PATH') . $doc['document_file'];
            Log::info('Adding PDF to merger: ' . $filePath);

            $merger->addPDF($filePath, 'all');
        }
        
        $fileName = trim($company->name) . time() . '.pdf';
        Log::info('Merging documents into: ' . $fileName);

        $merger->merge();
        $merger->save(storage_path($fileName));
        
        Log::info('Documents merged successfully.');

    } catch (\Throwable $th) {
        Log::error('Error during document merging: ' . $th->getMessage());
        throw $th;
    }
}

 
      
 
     }

// 1. Generate pdf from htmls and store it
// 2. Merge PDFS and merge with generated pdf
// 3. store them and return the merged pdf to the user