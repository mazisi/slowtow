<?php

namespace App\Http\Controllers;

use App\Models\LicenceType;
use Illuminate\Http\Request;

class InsertTypesController extends Controller
{
  public function insert(){
  $inserts = collect([ 
  ["type" => "Brewers Liquor Licence"],
  ["type" => "Club Liquor Licence"],
  ["type" => "Gaming Premeses Liquor Licence"],
  ["type" => "Grocers Wine Licence"],
  ["type" => "Hotel Liquor Licence"],
  ["type" => "Liquor Club Liquor Licence"],
  ["type" => "Liquor Store Liquor Licence"],
  ["type" => "Micro Manufacturer Liquor Licence"],
  ["type" => "Nightclub Liquor Licence"],
  ["type" => "Pub Liquor Licence"],
  ["type" => "Restaurant Liquor Licence"],
  ["type" => "Retail"],
  ["type" => "Shebeen"],
  ["type" => "Sorghum"],
  ["type" => "Special Liquor Licence Eating House"],
  ["type" => "Special Liquor Licence Off Premise Consumption"],
  ["type" => "Special Liquor Licence On Premise"],
  ["type" => "Special Liquor Licence Other"],
  ["type" => "Special Liquor Licence Section 28"],
  ["type" => "Special Liquor Licence Tavern"],
  ["type" => "Special Off Consumption Licence Gw"],
  ["type" => "Special Off Consumption Licence Ls"],
  ["type" => "Sports Facility Liquor Licence"],
  ["type" => "Theatre Liquor Licence"],
  ["type" => "Venue Liquor Licence"],
  ["type" => "Water Vessel Liquor Licence"],
  ["type" => "Wholesale Liquor Licence"],
 ]);
        foreach($inserts as $insert){
            LicenceType::insert([
                "licence_type" => $insert["type"]
            ]);
        }
        return "done!!!";
        
    }
}
