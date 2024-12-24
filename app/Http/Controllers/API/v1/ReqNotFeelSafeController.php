<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\AutoLoadController;
use App\Models\Requests;
use Illuminate\Http\Request;
use DB;
use Twilio\Rest\Client;

class ReqNotFeelSafeController extends Controller
{

   public function __construct()
   {
      $this->limit=20;   
   }

  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function UpdateReq(Request $request)
  {
    $lat =$request->get('lat');
    $lng =$request->get('lng');
    $user_name = $request->get('user_name');
    $user_cat = $request->get('user_cat');
    $user_id = $request->get('user_id'); 
    $trip_id =$request->get('trip_id');
    $date_heure = date('Y-m-d H:i:s');

    $sql = DB::table('tj_user')
    ->select('telephone')
    ->where('id_categorie_user','=',1)
    ->get();

    foreach($sql as $row) {
        $to_number= $row->telephone;
        $from_number = '+17082953786';
    }
    
    if($user_cat == "driver"){
        
        $id_user = $request->get('id_user');
        $sql = DB::table('tj_vehicule')
        ->select('brand','model','color','numberplate')
        ->where('id_conducteur','=',DB::raw($user_id))
        ->get();
        foreach($sql as $row) {
            $car_model = $row->model;
            $car_brand = $row->brand;
            $car_color = $row->color;
            $car_numberplate = $row->numberplate;
        }

        $check=DB::table('tj_requete')->select('id')->where('id','=',$trip_id)->get();

        if (count($check)>0){

            $insertdata = DB::insert("insert into tj_sos(ride_id,latitude,longitude,creer,status) values ('" . $trip_id . "','" . $lat . "','" . $long . "','" . $creer . "','".$status."') ");
            if($sql->count() > 0){
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successful';
                $response['data'] = $row;
            } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Failed';
            }
        }  

    }elseif($user_cat == 'user_app'){

        $sql = DB::table('tj_requete')
        ->select('id_conducteur')
        ->where('id_user_app','=',DB::raw($user_id))
        ->where('statut','=','on ride')
        ->get();

        if($sql->count() > 0){

            foreach($sql as $row){
                $id_conducteur = $row->id_conducteur;
            }
        
            if($id_conducteur>0){
                $query = DB::table('tj_vehicule')
                ->select('brand','model','color','numberplate')
                ->where('id_conducteur','=',DB::raw($id_conducteur))
                ->get();
                
                foreach($query as $row_query) {

                    $car_model = $row_query->model;
                    $car_brand = $row_query->brand;
                    $car_color = $row_query->color;
                    $car_numberplate = $row_query->numberplate;
            
                }
                
                if($query->count() > 0){
                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'successful';
                    $response['data'] = $row_query;
                } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Failed';
                }    
            
            } else{
                $response['success'] = 'Failed';
                $response['error'] = 'Not Found';
            }
        
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Not Found';
        }        
    } else {
        $response['success'] = 'Failed';
        $response['error'] = 'Not Found';
    }  
    
    return response()->json($response);
  }

}