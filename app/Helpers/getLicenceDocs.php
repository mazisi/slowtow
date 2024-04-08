<?php

 if(!function_exists('getLicenceDocs')){
     function getLicenceDocs($licence){
        $duplicate_original_issued = null;
   
            if ($licence !== null) {
                if (isset($licence->duplicate_originals) && $licence->duplicate_originals->count() > 0) {
                    $firstDuplicateOriginal = $licence->duplicate_originals[0];                   
            
                    if (isset($firstDuplicateOriginal->duplicate_documents) && !empty($firstDuplicateOriginal->duplicate_documents)) {
                       
                        foreach ($firstDuplicateOriginal->duplicate_documents as $document) {
                            
                            if ($document->doc_type === 'Duplicate Original Issued') {
                                
                                $duplicate_original_issued = $document;
                                break; 
                            }
                        }
                    }
                }
            }


$original_licence_delivered = null;


if ($licence !== null) {

    if (isset($licence->duplicate_originals) && count($licence->duplicate_originals) > 0) {
       
        $firstDuplicateOriginal = $licence->duplicate_originals[0];        
    
        if (isset($firstDuplicateOriginal->duplicate_documents) && !empty($firstDuplicateOriginal->duplicate_documents)) {
        
            foreach ($firstDuplicateOriginal->duplicate_documents as $document) {
                
                if ($document->doc_type === 'Duplicate-Original-Licence-Delivered') {
                    $original_licence_delivered = $document;
                    break; 
                }
            }
        }
    }
}

            return json_encode([
                'duplicate_original_issued' => $duplicate_original_issued,
                'original_licence_delivered' => $original_licence_delivered
            ]);
     }
 }

