<?php

//Licence keys for retail Licence
function retail() {
    return[
        100 => 'Client Quoted',
        200 => 'Client Invoice',
        300 => 'Deposit Paid',
        400 => 'Payment To The Liquor Board',
        500 => 'Prepare New Application',
        510 => 'Premises Complete and Trading', //New stage added later
        600 => 'Scanned Application',
        700 => 'Lodged with Municipality',      //Only if its Mpumalanga
        800 => 'Municipal Comments',            //Only if its Mpumalanga
        900 => 'Completed Application Scanned',
        1000 => 'Lodged with MER',
        1100 => 'Lodged with Magistrate',       //Only if its Limpopo & North West
        1200 => 'Lodged with DPO',              //Only if its Limpopo & North West
        1300 => 'Police Report',                //Only if its Limpopo & North West
        1400 => 'Lodged With Liquor Board',
        1500 => 'Application Lodged (Proof of Lodgement)',
        1600 => 'Additional Documents/Information',
        1700 => 'Initial Inspection',           //Only if privince !== North West
        1800 => 'Liquor Board Inspection',
        1900 => 'Activation Fee Requested',
        2000 => 'Client Finalisation Invoice',
        2100 => 'Finalisation Paid',
        2200 => 'Activation Fee Paid',
        2300 => 'Licence Issued',
        2400 => 'Licence Delivered',
    ];
}

function wholesale() {
    return[
        100 => 'Client Quoted',
        200 => 'Deposit Invoiced',
        300 => 'Deposit Paid',
        400 => 'Prepare New Application',
        500 => 'Application Submitted',
        600 => 'Initial Application Fee',
        700 => 'Application Lodged',
        800 => 'Additional Documents Request',           
        900 => 'NLA 6 Proposed',
        1000 => 'NLA 7 Submitted',
        1100 => 'NLA 8 Issued',       //Only if its Limpopo & North West
        1200 => 'Activation Fee',              //Only if its Limpopo & North West
        1300 => 'NLA 9 Issued',                //Only if its Limpopo & North West
        1400 => 'Original Licence',
        1500 => 'Original Licence Delivered'
    ];
}

function renewals() {
    return[
        100 => 'Renewal Notice Received',
        200 => 'Turnover Information Requested',
        300 => 'Turnover Information Received',
        400 => 'Annual Return Submited',
        500 => 'Client Invoiced',
        600 => 'Client Paid',
        700 => 'Payment to the National Liquor Authority',
        800 => 'Renewal Forms Sent',           
        900 => 'Renewal Forms Received',
        1000 => 'Renewal Forms Preparation',
        1100 => 'Renewal Submitted',    
        1200 => 'Additional Documents/Information Requested',           
        1300 => 'Renewal Pending QA',             
        1400 => 'Renewal Awaiting Sign Off',
        1500 => 'Renewal Approved',
    ];
}


