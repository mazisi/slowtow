<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    
    public function index()
    {
        $company_prop = 'companies';
        return Inertia::render('Company',['companies'=> $company_prop]);
    }

    public function create()
    {
        return Inertia::render('CreateNewCompany');
    }

    public function store(Request $request)
    {
        Company::create(
            $request->validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['required', 'max:50', 'email'],
            ])
        );
    }

    public function show()
    {
        return Inertia::render('ViewCompany',['companies'=> 'companies']);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
