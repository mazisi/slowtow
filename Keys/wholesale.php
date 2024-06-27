<?php

//Licence keys for retail Licence

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



    function alterations() {
        return[
           '100' => 'quoted',
           '200' => 'Client Invoiced',
           '300' => 'Client Paid',
           '400' => 'Prepare NLA 14 Application',
           '500' => 'Payment to the National Liquor Authority',
           '600' => 'NLA 14 Application Lodged',
           '700' => 'Additional Documents Requested',
           '800' => 'NLA 15 Certificate Issued',
           '900' => 'NLA 15 Certificate Delivered'
        ];

        function transfers() {
            return[
                100 => 'Client Quoted',
                200 => 'Deposit Invoiced',
                300 => 'Client Paid',
                400 => 'Transfer Documents Preparation',
                500 => 'Proof of Payment to the National Liquor Authority',
                600 => 'Scanned Application',
                700 => 'Application Lodged',
                800 => 'Additional Documents Requested',
                900 => 'Transfer Certificate Issued',
                1000 => 'Transfer Certificate  Delivered'
            ];
        }
}
