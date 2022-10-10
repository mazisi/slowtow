<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiquorBoardRequest;

class LiquorBoardRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'licence_id' => 'required|exists:licences,id',
                'requestBody' => 'required|string'
            ]);
            LiquorBoardRequest::create([
                'licence_id' => $request->licence_id,
                'body' => $request->requestBody
            ]);
            return back()->with('success','Board request created successfull.');
        } catch (\Throwable $th) {
            return back()->with('error','Unable to create board request.');
        }

    }
}
