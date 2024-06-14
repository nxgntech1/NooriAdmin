<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Drivers;
use App\Models\UserApp;
use App\Models\Note;
use Illuminate\Http\Request;
use DB;
class DriverRideReviewController extends Controller
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


    public function getRideReview(Request $request)
    {
        $months = array ("January"=>'Jan',"February"=>'Fev',"March"=>'Mar',"April"=>'Avr',"May"=>'Mai',"June"=>'Jun',"July"=>'Jul',"August"=>'Aou',"September"=>'Sep',"October"=>'Oct',"November"=>'Nov',"December"=>'Dec');


      $ride_id =$request->get('ride_id');
      $driver_id = $request->get('driver_id');
      $sql = DB::table('tj_conducteur')
      ->crossJoin('tj_note')
      ->crossJoin('tj_user_app')
      ->crossJoin('tj_requete')
      ->select('tj_user_app.id as idUserApp', 'tj_note.id as idNote','tj_note.ride_id', 'tj_conducteur.id as idConducteur', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.photo_path', 'tj_note.creer', 'tj_note.modifier')
      ->where('tj_note.id_conducteur','=',DB::raw('tj_conducteur.id'))
      ->where('tj_note.id_user_app','=',DB::raw('tj_user_app.id'))
      ->where('tj_note.ride_id','=',DB::raw('tj_requete.id'))
      ->where('tj_note.id_conducteur','=',$driver_id)
      ->where('tj_note.ride_id','=',$ride_id)
      ->orderBy('tj_note.id','desc')
      ->get();

      // output data of each row
      $output = array();
      foreach($sql as $row)
      {

          $id_driver = $row->idConducteur;
          $id_user_app = $row->idUserApp;
          $row->idConducteur=(string)$row->idConducteur;
          $row->idUserApp=(string)$row->idUserApp;
          $row->idNote=(string)$row->idNote;
          // Note conducteur
          $sql_note = DB::table('tj_note')
          ->select('niveau', 'comment')
          ->where('id_user_app','=',$id_user_app)
          ->where('id_conducteur','=',$id_driver)
          ->get();
          foreach($sql_note as $row_note)
      {
          if(!empty($row_note)){
              $row->niveau = $row_note->niveau;
              $row->comment = $row_note->comment;
          }else{
              $row->niveau = "";
              $row->comment = "";
          }
      }
          $row->creer = date("d", strtotime($row->creer))." ".$months[date("F", strtotime($row->creer))].". ".date("Y", strtotime($row->creer));

          $output[] = $row;
      }


      if(count($sql)>0){
          $response['success']= 'success';
          $response['error']= null;
          $response['message'] = 'Successfully';
          $response['data'] = $output;


      }else{
          $response['success']= 'Failed';
          $response['error']= 'Failed to fetch data';
      }
          return response()->json($response);

      }

}
