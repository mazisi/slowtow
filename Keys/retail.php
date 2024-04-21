<?php

//Licence keys for retail Licence
function licence() {
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

function alterations() {
    return[
        100 => 'Client Quoted',
        200 => 'Deposit Invoiced',
        300 => 'Deposit Paid',
        400 => 'Prepare Alterations Application',
        500 => 'Payment to the Liquor Board',
        600 => 'Alterations Lodged',
        700 => 'Alterations Certificate Issued',
        800 => 'Alterations Delivered'
    ];
}
    function renewals() {
        return[
            100 => 'Client Quoted',
            200 => 'Deposit Invoiced',
            300 => 'Deposit Paid',
            400 => 'Payment To The Liquor Board',
            500 => 'Renewal Issued',
            600 => 'Renewal Delivered'
        ];

        
}

function nominations() {
    return[
        100 => 'Client Quoted',
        200 => 'Deposit Invoiced',
        300 => 'Deposit Paid',
        400 => 'Payment To The Liquor Board',
        500 => 'Select Person(s) To Nominate',
        600 => 'Prepare Nomination Application',
        700 => 'Scanned Application',
        800 => 'Application Lodged',
        900 => 'Nomination Issued',
        1000 => 'Nomination Delivered',
        
    ];

    function transfers() {
        return[
            100 => 'Client Quoted',
            200 => 'Deposit Invoiced',
            300 => 'Client Paid',
            400 => 'Prepare Transfer Application',
            500 => 'Payment To The Liquor Board',
            600 => 'Scanned Application',
            700 => 'Application Lodged',
            800 => 'Activation Fee Paid',
            900 => 'Transfer Issued',
            1000 => 'Transfer Delivered'
        ];
    }
}