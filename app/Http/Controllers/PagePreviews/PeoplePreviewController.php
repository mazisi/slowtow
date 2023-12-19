<?php

namespace App\Http\Controllers\PagePreviews;

use App\Http\Controllers\Controller;
use App\Models\People;
use Illuminate\Http\Request;
class PeoplePreviewController extends Controller
{
    function printDocument(Request $request) {
        $person = People::with('people_documents')->whereSlug($request->slug)->first();
        dd($person);
    }
}
