<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Drivers;
use App\Models\UserApp;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

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

    public function getVehicleDetails(Request $request)
    {

        $ride_id =$request->get('ride_id');
        

        // $pdo = DB::getPdo();
        // $stmt = $pdo->prepare('CALL GetBookingAndUserInfo(:bookingid)');
        // $stmt->bindParam(':bookingid', $ride_id, PDO::PARAM_INT);
        // $stmt->execute();

        // // Fetch the first result set
        // //$details = $stmt->fetchAll(PDO::FETCH_OBJ);

        // // Move to the next result set
        // //$stmt->nextRowset();

        // // Fetch the second result set
        // $booking = $stmt->fetchAll(PDO::FETCH_OBJ);
        // //$response = $booking[0] ?? null;


        // //$output = array();

        // foreach($booking as $row)
        // {

        //     $row->from = $row->depart_name;
        //     $row->to = $row->destination_name;
        //     $row->distance = (string) $row->distance;
        //     $row->username = $row->prenom . $row->nom;

        //     $output[] = $row;
        // }

        // SINGLE RECORD 
        // // // // $result = DB::select('CALL GetBookingAndUserInfo(?)', [$ride_id]);
        // // // // $user = $result[0] ?? null;

        // // // // // Or if you expect multiple results
        // // // // foreach ($result as $row) {
        // // // //     // Process each row
        // // // //    $output[] = $row;
        // // // // }

        //// MULTIPLE RECORDS 
        // // // $pdo = DB::getPdo();
        // // // $stmt = $pdo->prepare('CALL GetBookingAndUserInfo(:bookingid)');
        // // // $stmt->bindParam(':bookingid', $ride_id, PDO::PARAM_INT);
        // // // $stmt->execute();

        // // // $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        // // // // If you expect only one result, you can get the first row
        // // // $user = $result[0] ?? null;

        // // // // Or if you expect multiple results
        // // // foreach ($result as $row) {
        // // //     // Process each row
        // // //     $output[] = $row;
        // // // }


        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('CALL GetBookingAndUserInfo(:bookingid)');
        $stmt->bindParam(':bookingid', $ride_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the first result set
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);

         foreach ($user as $usersrow) {
            // Process each row
            $output[0] = $usersrow;
        }

        // Move to the next result set
        $stmt->nextRowset();

        // Fetch the second result set
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($orders as $ordersrow) {
            // Process each row
            $output[1] = $ordersrow;
        }

       
        if (!empty($output)) {
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Successfully';
            $response['users_data'] = $output[0];
            $response['orders_data'] = $output[1];
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
        }

        return response()->json($response);
    }



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
