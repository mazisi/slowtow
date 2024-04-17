<?php

use App\Models\People;
use App\Models\Company;
use App\Models\LicenceType;

/**
 * Retrieves the name of the licence holder based on the provided licence.
 *
 * @param object $licence The licence object to retrieve the holder's name from.
 * @return string The name of the licence holder.
 */
function getLicenceHolder($licence){
    if($licence->belongs_to == 'Company'){
        $company = Company::find($licence->company_id);
        return $company->name;
    }else if($licence->belongs_to == 'Individual'){
        $individual = People::find($licence->people_id);
        return $individual->full_name;
    }
    return '';
}

/**
 * Retrieves the IDs of LicenceType records based on the provided province(s).
 *
 * @return array An array of LicenceType IDs.
 */
function getLicenceTypeByProvince(){
     $provinces = explode(',',request('province'));
     return LicenceType::whereIn('province', $provinces)->get(['id'])->pluck('id')->toArray();
}