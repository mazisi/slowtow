<?php

namespace App\Http\Controllers;

use App\Models\LicenceType;
use Illuminate\Http\Request;

class InsertTypesController extends Controller
{
  public function insert(){
  $inserts = collect([ 
  ["type" => "Accommodation Liquor Licence A"],
  ["type" => "Accommodation Liquor Licence B"],
  ["type" => "Accommodation Liquor Licence C"],
  ["type" => "Accommodation Liquor Licence D"],
  ["type" => "Airport Lounge Liquor Licence"],
  ["type" => "Airport Supply Liqour Licence"],
  ["type" => "Brewers Liquor Licence"],
  ["type" => "Club Liquor Licence"],
  ["type" => "Gaming Premeses Liquor Licence"],
  ["type" => "Grocers Wine Licence"],
  ["type" => "Hotel Liquor Licence"],
  ["type" => "Liquor Club Liquor Licence"],
  ["type" => "Liquor Store Liquor Licence"],
  ["type" => "Micro Manufacturer Liquor Licence"],
  ["type" => "Nightclub Liquor Licence"],
  ["type" => "Occasional Liquor Licence"],
  ["type" => "Producers Licence"],
  ["type" => "Pub Liquor Licence"],
  ["type" => "Restaurant Liquor Licence"],
  ["type" => "Retail Sale And Supply Of Liquor For Consumption Off The Registered Premises Where Liquor Is Being Sold"],
  ["type" => "Retail Sale And Supply Of Liquor For Consumption On And Off The Registered Premises Where Liquor Is Being Sold"],
  ["type" => "Retail Sale And Supply Of Liquor For Consumption On The Registered Premises Where Liquor Is Being Sold"],
  ["type" => "Shebeen Off Consumption Liquor Licence"],
  ["type" => "Shebeen On Consumption Liquor Licence"],
  ["type" => "Shebeen On Off Consumption Liquor Licence"],
  ["type" => "Sorghum BEEr Brewers Licence"],
  ["type" => "Sorghum BEEr Liquor Licence On Premise"],
  ["type" => "Sorghum BEEr Off Consumption Liquor Licence"],
  ["type" => "Sorghum BEEr On And Off Consumption Liquor Licence"],
  ["type" => "Sorghum BEEr On Consumption Liquor Licence"],
  ["type" => "Sorgum BEEr Liquor Licence Off Premise Consumption"],
  ["type" => "Special Liquor Licence Accommodation"],
  ["type" => "Special Liquor Licence Eating House"],
  ["type" => "Special Liquor Licence Off Premise Consumption"],
  ["type" => "Special Liquor Licence On Premise"],
  ["type" => "Special Liquor Licence Other"],
  ["type" => "Special Liquor Licence Section 28"],
  ["type" => "Special Liquor Licence Tavern"],
  ["type" => "Special Off Consumption Licence Gw"],
  ["type" => "Special Off Consumption Licence Ls"],
  ["type" => "Sports Facility Liquor Licence"],
  ["type" => "Tavern Liquor Licence"],
  ["type" => "Temporary Licence"],
  ["type" => "Temporary Liquor Licence"],
  ["type" => "Theatre Liquor Licence"],
  ["type" => "Unknown"],
  ["type" => "Venue Liquor Licence"],
  ["type" => "Water Vessel Liquor Licence"],
  ["type" => "Wholesale Liquor Licence"],
  ["type" => "Wine Farmers Liquor Licence"],
  ["type" => "Wine House Liquor Licence"],
 ]);
        foreach($inserts as $insert){
            LicenceType::insert([
                "licence_type" => $insert["type"]
            ]);
        }
        return "done!!!";
        
    }
}
