<?php

namespace App\Http\Controllers;

use App\Models\LicenceType;
use Illuminate\Http\Request;

class InsertTypesController extends Controller
{
  public function insert(){dd('ok');
  $inserts = collect([ 
  ["type" => "Club liquor licence"],
  ["type" => "Dance Hall liquor licence"],
  ["type" => "Discretionary off-consumption liquor licence"],
  ["type" => "Discretionary on-consumption liquor licence"],
  ["type" => "Gaming premises liquor licence"],
  ["type" => "Grocer’s Wine liquor licence"],
  ["type" => "Hotel liquor licence"],
  ["type" => "Liquor Store liquor licence"],
  ["type" => "Micro-manufacturing liquor licence"],
  ["type" => "National Distribution liquor licence"],
  ["type" => "National Manufacturing and Distribution liquor licence"],
  ["type" => "National Manufacturing liquor licence"],
  ["type" => "Night Club liquor licence"],
  ["type" => "Occasional liquor licence"],
  ["type" => "Pool Club liquor licence"],
  ["type" => "Pub liquor licence"],
  ["type" => "Restaurant liquor licence"],
  ["type" => "Sorghum Beer off-consumption liquor licence"],
  ["type" => "Sorghum Beer on-consumption liquor licence"],
  ["type" => "Sports Ground liquor licence"],
  ["type" => "Tavern liquor licence"],
  ["type" => "Theatre liquor licence"]
 ]);
        foreach($inserts as $insert){
            LicenceType::insert([
                "licence_type" => $insert["type"]
            ]);
        }
        return "done!!!";
        
    }
}
