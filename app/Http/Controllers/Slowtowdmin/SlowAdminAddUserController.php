<?php

namespace App\Http\Controllers\Slowtowdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlowAdminAddUserController extends Controller
{
    public function index(){
        return Inertia::render('SlowtowAdminAddUser');
    }
}
