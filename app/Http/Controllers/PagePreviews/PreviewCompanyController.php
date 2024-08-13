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
// use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
class PreviewCompanyController extends Controller
{
    function preview($slug) {

        $html= view('pdfs.company', ['company' => Company::whereSlug($slug)->firstOrFail()])->render();
        //  dd($html);
        $company = Company::whereSlug($slug)->firstOrFail();
        Pdf::loadHTML($html)->save(storage_path('app/public/').''.trim($company->name).'.pdf');
        $this->mergeDocuments($company);
        // return view('pdfs.company', ['company' => $company]);

        // return Inertia::render('PagePreviews/Company/CompanyPreview',
        // ['company' => $company]);
    }

    public function mergeDocuments($company){
        // $merger = PDFMerger::init();
        try {
            $documents = collect(); 
            $i=0;

            $companyLicence = Licence::where('company_id', $company->id)->get();
            
            foreach ($companyLicence as $licence) {
                $originals = LicenceDocument::where('licence_id', $licence->id)
                                            ->where('document_type', 'Original-Licence')
                                            ->get();
                                            
                $duplicates = LicenceDocument::where('licence_id', $licence->id)
                                             ->where('document_type', 'Duplicate-Licence')
                                             ->get();
    
                $documents = $documents->merge($originals)->merge($duplicates); // Merge the collections
            }

            $pdf = PDFMerger::init(); 

            
                 $pdf->addPDF("https://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf", 'all');
                 $pdf->addPDF("https://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf", 'all');

            //  foreach ($documents as $doc) {
            //     $filePath = env('BLOB_FILE_PATH') . $doc->document_file;
            //     Log::info('Adding PDF to merger: ' . $filePath);    
            //     $pdf->addPDF($filePath, 'all');
            //   }
              $pdf->addPDF(storage_path('/app/public/').trim($company->name).'.pdf', 'all');
                $pdf->merge();
                $pdf->save(storage_path("fuckit.pdf"));

           dd("done");
            Log::info('Total documents to merge: ' . count($documents));
    
            // foreach ($documents as $doc) {
            //     $i=$i+1;
            //     $filePath = env('BLOB_FILE_PATH') . $doc->document_file;
            //     Log::info('Adding PDF to merger: ' . $filePath);    
            //     $merger->addPDF($filePath, 'all');
            // }

            // $merger->addPDF(storage_path('/app/public/').trim($company->name).'.pdf', 'all');
            // // dd($merger); 
            // $fileName = 'fuckitt.pdf';
            // // $fileName = $company->name . time() . '.pdf';
            // Log::info('Merging documents into: ' . $fileName);
    
            // $merger->merge();
            // Log::info('Documents merged successfully.');
            // $merger->save(storage_path('/app/public/' . $fileName));
            
            // Log::info('Documents saved successfully.');
    
        } catch (\Throwable $th) {
            Log::error('Error during document merging: ' . $th->getMessage());
            throw $th;
        }
    }
    

 
      
 
     }

// 1. Generate pdf from htmls and store it
// 2. Merge PDFS and merge with generated pdf
// 3. store them and return the merged pdf to the user