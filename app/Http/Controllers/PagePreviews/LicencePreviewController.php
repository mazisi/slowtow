<?php

namespace App\Http\Controllers\PagePreviews;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use App\Models\Nomination;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Models\AlterationDocument;
use App\Models\NominationDocument;
use App\Http\Controllers\Controller;

class LicencePreviewController extends Controller
{
    function preview($slug) {
        $licence  = 
        Licence::with('licence_documents','company','people','nominations.nomination_documents','transfers','alterations.documents','licence_stage_dates')
        ->whereSlug($slug)->firstOrFail();
        // if($licence->type == 'wholesale') {
        //     return $this->handleWholesale($licence);
        // }

        $nom = Nomination::where('licence_id', $licence->id)->latest()->first(['id']);
        $original_licence = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->latest()->first();
        $duplicate_original_licence = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->latest()->first();
        $payment_to_the_liquor_board = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Payment To The Liquor Board')->latest()->first();
        
        if(!$duplicate_original_licence) {
            $duplicate_original_licence = $payment_to_the_liquor_board;
        }

        $latest_renewal = NominationDocument::where('nomination_id',$nom?->id)->where('doc_type','Latest Renewal/Licence')->latest()->first();
        $latest_renewal_alt = NominationDocument::where('nomination_id',$nom?->id)->where('doc_type','Payment To The Liquor Board')->latest()->first();
        if(!$latest_renewal) {
            $latest_renewal = $latest_renewal_alt;
        }
        $appointment_of_managers = null;
       if($licence->type == 'retail'){
        $appointment_of_managers = NominationDocument::where('nomination_id',$nom?->id)->where('doc_type','Nomination Issued')->latest()->first();
        $appointment_of_managers_alt = NominationDocument::where('nomination_id',$nom?->id)->where('doc_type','Nomination Lodged')->latest()->first();
        if(!$appointment_of_managers) {
            $appointment_of_managers = $appointment_of_managers_alt;
        }
       }
        
        $transfer = LicenceTransfer::where('licence_id', $licence->id)->latest()->first(['id']);
        $transfer_certificate_issued = TransferDocument::where('doc_type','Transfer Issued')->where('licence_transfer_id', $transfer?->id)->latest()->first();
        $transfer_certificate_lodged = TransferDocument::where('doc_type','Transfer LogdedTransfer Issued')->where('licence_transfer_id', $transfer?->id)->latest()->first();
        if(!$transfer_certificate_issued) {
            $transfer_certificate_issued = $transfer_certificate_lodged;
        }

        $alteration = Alteration::where('licence_id', $licence->id)->first(['id']); 
        $alteration_document = AlterationDocument::where('alteration_id', $alteration?->id)->where('doc_type','Alterations Certificate Issued')->latest()->first();    
        $alteration_document_alt = AlterationDocument::where('alteration_id', $alteration?->id)->where('doc_type','Alterations Lodged')->latest()->first();    
        if(!$alteration_document) {
            $alteration_document = $alteration_document_alt;
        }

        $licenceTypes = LicenceType::get();
        return Inertia::render('PagePreviews/Licences/LicencePreview', 
        [
            'licence' => $licence,
            'original_licence' => $original_licence,
            'duplicate_original_licence' => $duplicate_original_licence,
            'latest_renewal' => $latest_renewal,
            'transfer_certificate_issued' => $transfer_certificate_issued,
            'alteration_document' => $alteration_document,
            'appointment_of_managers' => $appointment_of_managers,
            'licenceTypes' => $licenceTypes
    ]);
    }

    
}
