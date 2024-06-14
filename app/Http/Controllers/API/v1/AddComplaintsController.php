<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Complaints;
use Illuminate\Http\Request;
use DB;

class AddComplaintsController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rideType = $request->get('ride_type');
        $user_type = $request->get('user_type');
        if(!empty($rideType) && $rideType=="parcel"){

            $ride_id = $request->get('order_id');
            $complaints = Complaints::where('id_parcel',$ride_id)->where('user_type',$user_type)->first();

        }else{
            $ride_id = $request->get('order_id');
            $complaints = Complaints::where('id_ride',$ride_id)->where('user_type',$user_type)->first();
        }

        if(!empty($complaints)){
                $row = $complaints->toArray();
              $row['id']=(string)$row['id'];

              $output[] = $row;
            if(!empty($output)){

                $response['success']= 'success';
                $response['error']= null;
                $response['message']= 'successfully';
                $response['data'] = $output;
            }else{
              $response['success']= 'Failed';
              $response['error']= 'No Data Found';
              $response['message']= null;
            }

        }

        else{
            $response['success']= 'Failed';
            $response['error']= 'Not Found';
        }

        return response()->json($response);
    }

    public function register(Request $request)
    {
		$data = array();
        $data['title'] = $request->get('title');
        $rideType = $request->get('ride_type');
        $data['user_type'] = $request->get('user_type');
        if(!empty($rideType) && $rideType=="parcel"){
            $data['id_parcel'] = $request->get('order_id');
            $checkComplaint = Complaints::where('id_parcel', $request->get('order_id'))->where('user_type', $request->get('user_type'))->first();
        }else{
            $data['id_ride'] = $request->get('order_id');
            $checkComplaint = Complaints::where('id_ride', $request->get('order_id'))->where('user_type', $request->get('user_type'))->first();

        }
        $data['description'] = $request->get('description');
        if ($request->get('id_user_app')!=null){
            $data['id_user_app'] = $request->get('id_user_app');
        }
        
        if ($request->get('id_conducteur')!=null){
            $data['id_conducteur'] = $request->get('id_conducteur');
        }
        
        $data['status'] = 'initiated';
        $data['created'] = date('Y-m-d H:i:s');
        if(!empty($checkComplaint)){
        $row = $checkComplaint->toArray();
        }
        if(!empty($row)){
            $ins =  DB::table('tj_complaints')
            ->where('id', $row['id']) 
            ->update($data); 

        }else{

        $ins = DB::table('tj_complaints')->insert($data);
        }
        if ($ins) {

            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Complaint added successfully';
            $response['data'] = $data;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to add Complaint';
        }

        return response()->json($response);
    }
  
}