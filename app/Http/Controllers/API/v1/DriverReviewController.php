<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Drivers;
use App\Models\UserApp;
use App\Models\Note;
use Illuminate\Http\Request;
use DB;
class DriverReviewController extends Controller
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

  public function getData(Request $request)
  {
    $months = array ("January"=>'Jan',"February"=>'Fev',"March"=>'Mar',"April"=>'Avr',"May"=>'Mai',"June"=>'Jun',"July"=>'Jul',"August"=>'Aou',"September"=>'Sep',"October"=>'Oct',"November"=>'Nov',"December"=>'Dec');

    $driver_id =$request->get('driver_id');
    $sql = DB::table('tj_conducteur')
    ->crossJoin('tj_note')
    ->crossJoin('tj_user_app')
    ->select('tj_user_app.id as idUserApp', 'tj_note.id as idNote', 'tj_conducteur.id as idConducteur', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.photo_path', 'tj_note.creer', 'tj_note.modifier')
    ->where('tj_note.id_conducteur','=',DB::raw('tj_conducteur.id'))
    ->where('tj_note.id_user_app','=',DB::raw('tj_user_app.id'))
    ->where('tj_note.id_conducteur','=',$driver_id)
    ->orderBy('tj_note.id','desc')
    ->get();

    // output data of each row
    $output = array();
    foreach($sql as $row)
    {
        $row->idUserApp=(string)$row->idUserApp;
        $row->idNote=(string)$row->idNote;
        $row->idConducteur=(string)$row->idConducteur;
        $id_driver = $row->idConducteur;
        $id_user_app = $row->idUserApp;

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
