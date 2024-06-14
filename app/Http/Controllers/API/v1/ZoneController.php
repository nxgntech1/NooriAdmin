<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;

class ZoneController extends Controller
{

    public function getData(Request $request)
    {

        $zone = Zone::where('status','yes')->get();
        
        if(count($zone) > 0){
            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Zone successfully fetched';
            $response['data'] = $zone;
        }else {
            $response['success']= 'Failed';
            $response['error']= 'No Data Found';
            $response['message']= null;
        }

        return response()->json($response);
    }

    public function updateZone(Request $request)
    {

        $id_driver = $request->get('id_driver');
        $zone_id = $request->get('zone_id');
        
        if(!empty($id_driver) && !empty($zone_id)){
            
            $driver = Driver::find($id_driver);
            $driver->zone_id = $zone_id;
            $driver->save();

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Zone successfully updated';
           
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Zone id or Driver id is missing';
            $response['message']= null;
        }

        return response()->json($response);
    }

}
