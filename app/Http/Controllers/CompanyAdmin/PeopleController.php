<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class PeopleController extends Controller
{
    function index() {
        $company = Company::with('users','licences','people', 'company_documents')
        ->whereId(auth()->user()?->company[0]->id)
        ->latest()->paginate(30)->withQueryString();
        return Inertia::render('CompanyAdmin/People/MyPeople',[
            'company' => $company
        ]);
    }

    function show($id) {
        $user = User::find($id);dd($user);
        return Inertia::render('CompanyAdmin/People/ViewPerson',[
            'user' => $user
        ]);
    }
}
