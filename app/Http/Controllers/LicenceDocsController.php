<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Wholesale\WholesaleController;
use App\Models\Licence;
use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
//use Illuminate\Http\File;
use App\Models\LicenceDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class LicenceDocsController extends Controller
{
    /**
     * Store a document file, update the associated licence status and flags, and return a success message.
     *
     * @param Request $request The request object containing the document file, doc type, licence ID, num, and stage.
     * @return \Illuminate\Http\RedirectResponse The response with success or error message.
     */
    public function store(Request $request)
    {
        $request->validate([
            "document_file" => "required|mimes:pdf",
            "doc_type" => "required"
        ]);
        $licence = Licence::find($request->licence_id);
        $fileName = $this->generateFileName($request->document_file);
        $filePath = $this->storeDocumentFile($request->document_file, $fileName);
        
        if (fileExist($filePath)) {
            $fileModel = $this->createLicenceDocument($request, $fileName);
            $this->updateLicenceStatusAndFlags($request, $fileModel);
         if($licence->type == 'wholesale'){   
            return (new WholesaleController())->generateLicenceIssuedDocs($licence);
         }
            return back()->with('success', 'Document uploaded successfully.');
        } else {
            return back()->with('error', 'Azure storage could not be reached. Please try again.');
        }
    }

    /**
     * Delete a document file, update the associated licence status and flags, and return a success message.
     *
     * @param int $id The ID of the document to be removed.
     * @return \Illuminate\Http\RedirectResponse The response with success or error message.
     */
    public function destroy($id)
    {
        $model = LicenceDocument::find($id);
        $updateLicence = Licence::find($model->licence_id);

        $this->updateLicenceStatusAndFlags($model, $updateLicence);

        if (!is_null($model->document_file)) {
            $model->delete();
            return back()->with('success', 'Document removed successfully.');
        }

        return back()->with('error', 'An error occurred.');
    }

    /**
     * Merge all the documents associated with a licence, update the merged document file, and return a success message.
     *
     * @param int $licence_id The ID of the licence to merge the documents for.
     * @return \Illuminate\Http\RedirectResponse The response with success or error message.
     */
    public function merge($licence_id)
    {
        try {
            $exist = Licence::whereId($licence_id)->first(['id', 'merged_document']);

            $this->deleteExistingMergedDocument($exist);

            $mergedDocument = $this->mergeDocuments($exist);

            $this->updateMergedDocument($exist, $mergedDocument);

            return back()->with('success', 'Documents merged successfully.');
        } catch (\Throwable $th) {
            throw $th;
            //return back()->with('error','Error merging documents.');
        }
    }

    /**
     * Generate a unique file name for the document file.
     *
     * @param \Illuminate\Http\UploadedFile $documentFile The uploaded document file.
     * @return string The generated file name.
     */
    private function generateFileName($documentFile)
    {
        $removeSpace = str_replace(' ', '_', $documentFile->getClientOriginalName());
        $fileName = Str::limit(sha1(now()), 3) . str_replace('-', '_', $removeSpace);

        return $fileName;
    }

    /**
     * Store the document file and return the file path.
     *
     * @param \Illuminate\Http\UploadedFile $documentFile The uploaded document file.
     * @param string $fileName The file name for the document file.
     * @return string The file path.
     */
    private function storeDocumentFile($documentFile, $fileName)
    {
        $filePath = $documentFile->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

        return $filePath;
    }

    /**
     * Check if the file exists in the storage.
     *
     * @param string $filePath The file path.
     * @return bool True if the file exists, false otherwise.
     */


    /**
     * Create a new LicenceDocument model.
     *
     * @param Request $request The request object containing the document file, doc type, licence ID, num, and stage.
     * @param string $fileName The file name for the document file.
     * @return LicenceDocument The created LicenceDocument model.
     */
    private function createLicenceDocument($request, $fileName)
    {
        $fileModel = new LicenceDocument;
        $fileModel->document_name = $request->document_file->getClientOriginalName();
        $fileModel->licence_id = $request->licence_id;
        $fileModel->document_type = $request->doc_type;
        $fileModel->num = $request->num;
        $fileModel->document_file = env('AZURE_STORAGE_CONTAINER') . '/' . $fileName;
        $fileModel->save();

        return $fileModel;
    }

    /**
     * Update the licence status and flags based on the document type.
     *
     * @param Request|LicenceDocument $request The request object or LicenceDocument model.
     * @param Licence $licence The Licence model.
     * @return void
     */
    private function updateLicenceStatusAndFlags($request, $licence)
    {
        if ($request instanceof Request && $request->stage && intval($request->stage) >= 3500) {
            $licence->update(['is_new_app' => false]);
        }

        if ($request instanceof Request && $request->stage) {
            $licence->update(['status' => $request->stage]);
        }
    }

    /**
     * Delete the existing merged document file.
     *
     * @param Licence $licence The Licence model.
     * @return void
     */
    private function deleteExistingMergedDocument($licence)
    {
        if (!is_null($licence->merged_document)) {
            unlink(storage_path() . 'app/public/' . $licence->merged_document);
            $licence->update(['merged_document' => null]);
        }
    }

    /**
     * Merge all the documents associated with a licence.
     *
     * @param Licence $licence The Licence model.
     * @return string The file name of the merged document.
     */
    private function mergeDocuments($licence)
    {
        $merger = PDFMerger::init();

        $allDocs = LicenceDocument::where('licence_id', $licence->id)
            ->whereNotNull('num')
            ->orderBy('num', 'ASC')
            ->get(['id', 'document_file', 'licence_id', 'document_type']);

        $liquorBoardDoc = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Payment To The Liquor Board')
            ->get(['id', 'document_file']);

        $mergedCollections = $allDocs->merge($liquorBoardDoc);

        foreach ($mergedCollections as $doc) {
            $merger->addPDF(env('BLOB_FILE_PATH') . $doc->document_file, 'all');
        }

        $fileName = 'licence' . '_' . time() . '.pdf';
        $merger->merge();
        $merger->save(storage_path('/app/public/' . $fileName));

        return $fileName;
    }

    /**
     * Update the merged document file for the licence.
     *
     * @param Licence $licence The Licence model.
     * @param string $mergedDocument The file name of the merged document.
     * @return void
     */
    private function updateMergedDocument($licence, $mergedDocument)
    {
        $licence->update(['merged_document' => $mergedDocument]);
    }
}
