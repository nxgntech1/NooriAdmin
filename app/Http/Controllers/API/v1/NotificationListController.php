<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Notification;
use Illuminate\Http\Request;
use DB;
class NotificationListController extends Controller
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
    $months = array ("January"=>'Jan',"February"=>'Feb',"March"=>'Mar',"April"=>'Apr',"May"=>'May',"June"=>'Jun',"July"=>'Jul',"August"=>'Aug',"September"=>'Sep',"October"=>'Oct',"November"=>'Nov',"December"=>'Dec');

    $user_id =$request->get('user_id');
    $driver_id =$request->get('driver_id');

    if(!empty($driver_id)){
        $sql = DB::table('tj_notification')
        ->crossJoin('tj_conducteur')
        ->select('tj_notification.*', 'tj_conducteur.nom', 'tj_conducteur.prenom', 'tj_conducteur.photo_path')
        ->where('tj_conducteur.id','=',DB::raw('tj_notification.to_id'))
        ->where('tj_notification.to_id','=',$driver_id)
        ->whereIn('tj_notification.type',['ridenewrider', 'userconfirmed', 'forgotitem', 'paymentcompleted'])
        ->orderBy('tj_notification.id','desc')
        ->get();

    }else{
        $sql = DB::table('tj_notification')
        ->crossJoin('tj_conducteur')
        ->select('tj_notification.*', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.photo_path')
        ->where('tj_user_app.id','=',DB::raw('tj_notification.to_id'))
        ->where('tj_notification.to_id','=',$user_id)
        ->whereIn('tj_notification.type',['riderejected', 'rideonride', 'rideconfirmed', 'ridecompleted'])
        ->orderBy('tj_notification.id','desc')
        ->get();
    }
    // output data of each row
    $output = array();
    foreach($sql as $row)
    {
        $row->creer_modify = NotificationListController::timeago($row->creer);
        $row->id=(string)$row->id;
        $output[] = $row;
    }

        if(!empty($sql)){
            $response['success']= 'success';
            $response['error']= null;
            $response['data'] = $output;

        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to fetch data';
        }

        return response()->json($response);


    }
    public static function timeago($date) {
        $timestamp = strtotime($date);

        $strTime = array("second", "minute", "hour", "day", "month", "year");
        $length = array("60","60","24","30","12","10");

        $currentTime = time();
        if($currentTime >= $timestamp) {
             $diff     = time()- $timestamp;
             for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
             $diff = $diff / $length[$i];
             }

             $diff = round($diff);
             if($strTime[$i]==1){
                 return $diff . " " . $strTime[$i] . " ago ";
             }else{
                 return $diff . " " . $strTime[$i] . "s ago ";
             }
        }
     }
}
