<?php

namespace App\Http\Controllers\PagePreviews;

use App\Http\Controllers\Controller;
use App\Models\People;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeoplePreviewController extends Controller
{
    function printDocument(Request $request) {
        $person = People::with('people_documents')->whereSlug($request->slug)->first();
        // dd($person);
        return Inertia::render('PagePreviews/People/PeoplePreview', ['person' => $person]);
    }
}
