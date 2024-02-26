<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PeopleController extends Controller
{
    function index() {
        $company = Company::with('users','licences','people', 'company_documents')
        ->whereId(auth()->user()?->company[0]->id)
        ->latest()->paginate(10)->withQueryString();dd($company);
        return Inertia::render('CompanyAdmin/People/MyPeople',[
            'company' => $company
        ]);
    }
}
