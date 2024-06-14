<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\VehicleLocation;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use DB;
class SetLocationController extends Controller
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


  public function register(Request $request)
  {


        $nb_jour = $request->get('nb_jour');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $contact = $request->get('contact');
        $contact = str_replace("'","\'",$contact);
        $id_user_app = $request->get('id_user_app');
        $id_vehicule = $request->get('id_vehicule');
        $date_heure = date('Y-m-d H:i:s');
          
        $check=DB::table('tj_location_vehicule')
        ->whereBetween('date_debut',[$date_debut,$date_fin])
        ->whereBetween('date_fin',[$date_debut,$date_fin])
        ->where('statut','!=','rejected')
        ->where('id_vehicule_rental','=',$id_vehicule)->first();


        if (!empty($check)) {
          $response['success']='Failed';
          $response['error']='Already Booked For this date ';

        }else{
          $insertdata = DB::insert("insert into tj_location_vehicule(nb_jour,date_debut,date_fin,contact,statut,id_vehicule_rental,id_user_app,creer,modifier)
          values('".$nb_jour."','".$date_debut."','".$date_fin."','".$contact."','in progress','".$id_vehicule."','".$id_user_app."','".$date_heure."','".$date_heure."')");

          $id=DB::getPdo()->lastInsertId();
          $chknote = VehicleLocation::where('id',$id)->first();
          $row = $chknote->toArray();
          $row['id']=(string)$row['id'];
          
          if ($chknote->count() > 0) {
              $response['success']= 'success';
              $response['error']= null;
              $response['message']= 'Location Set successfully';
              $response['data'] = $row;
          } else {
              $response['success']='Failed';
              $response['error']= 'Failed to insert location';
          }
        }



    return response()->json($response);


}

}
