<?php

namespace App\Http\Controllers;

use App\Models\AdditionalDoc;
use Illuminate\Http\Request;

class AdditionalDocsController extends Controller
{
    function store(Request $request) {
        dd($request);
        AdditionalDoc::store([

        ]);
        
    }
}
