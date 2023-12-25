<?php

namespace App\Http\Controllers\PagePreviews;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TemporalLicence;

class TempLicencesPreviewController extends Controller
{
    function preview() {
        $licence = TemporalLicence::with('company','people','temp_documents')->whereSlug(request('slug'))->firstOrFail();
        return Inertia::render('PagePreviews/TempLicences/TempLicencesPreview',['licence' => $licence]);
    }
}
