<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use DB;
class DeleteFavoriteRideController extends Controller
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

  public function deleteFavoriteRide(Request $request)
  {
    $id_requete_fav = $request->get('id_ride_fav');
   
if(!empty($id_requete_fav)){
    $updatedata =  DB::update('update tj_favorite_ride set statut = ? where id = ?',['no',$id_requete_fav]);

    if (!empty($updatedata)) {
      $response['success']= 'success';
      $response['error']= null;
      $response['message']= 'Data Deleted Successfully';        
      
    } else {
      $sql = DB::table('tj_favorite_ride')
      ->select('statut')
      ->where('id','=',$id_requete_fav)
      ->get();
      foreach($sql as $row){
        if($row->statut == 'no'){
          $response['success']= 'Failed';
          $response['error']='Already Deleted';
        }
      }
      if(empty($row))
      {
        $response['success']= 'Failed';
        $response['error']='Not Found';
      }
     
    }
  } else{
    $response['success']= 'Failed';
    $response['error']='Id Required';
  }
    return response()->json($response);
  }
       
}