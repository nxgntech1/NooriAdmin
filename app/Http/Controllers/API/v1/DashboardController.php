<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Requests;
use App\Models\Commission;
use Illuminate\Http\Request;
use DB;
class DashboardController extends Controller
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
    $id_diver =  $request->get('id_diver');
    $date_start = date('Y-m-d 00:00:00');
    $date_end = date('Y-m-d 23:59:59');
    dd($id_driver);
    $sql = DB::table('tj_requete')
    ->select(DB::raw("COUNT(id) as nb_new"))
    ->where('statut','=','new')
    ->where('id_conducteur','=',$id_diver)
    ->get();

    foreach($sql as $row){
        $row->nb_new = $row->nb_new;
}
        $sql_nb_confirmed = DB::table('tj_requete')
        ->select(DB::raw("COUNT(id) as nb_confirmed"))
        ->where('statut','=','confirmed')
        ->where('id_conducteur','=',$id_diver)
        ->get();

        if(!empty($sql_nb_confirmed)){
            foreach($sql_nb_confirmed as $row_nb_confirmed){
            $nb_confirmed = $row_nb_confirmed->nb_confirmed;
            }
        }else{
            $nb_confirmed = "0";
        }
        $row->nb_confirmed = $nb_confirmed;


     // Nb confirmed
     $sql_nb_onride = DB::table('tj_requete')
     ->select(DB::raw("COUNT(id) as nb_onride"))
     ->where('statut','=','on ride')
     ->where('id_conducteur','=',$id_diver)
     ->get();

     if(!empty($sql_nb_onride)){
         foreach($sql_nb_onride as $row_nb_onride){
         $nb_onride = $row_nb_onride->nb_onride;
         }
     }else{
         $nb_onride = "0";
     }
     $row->nb_onride = $nb_onride;

      // Nb confirmed
      $sql_nb_completed = DB::table('tj_requete')
      ->select(DB::raw("COUNT(id) as nb_completed"))
      ->where('statut','=','completed')
      ->where('id_conducteur','=',$id_diver)
      ->get();

      if(!empty($sql_nb_completed)){
        foreach($sql_nb_completed as $row_nb_completed){
        $nb_completed = $row_nb_completed->nb_completed;
        }
    }else{
        $nb_completed = "0";
    }
    $row->nb_completed = $nb_completed;

    // Nb sales
    $sql_nb_sales = DB::table('tj_requete')
      ->select(DB::raw("COUNT(id) as nb_sales"))
      ->where('statut','=','completed')
      ->where('id_conducteur','=',$id_diver)
      ->where('creer','>=','$date_start')
      ->where('creer','<','$date_end')
      ->get();

      if(!empty($sql_nb_sales)){
        foreach($sql_nb_sales as $row_nb_sales){
        $nb_sales = $row_nb_sales->nb_sales;
        }
    }else{
        $nb_sales = "0";
    }
    $row->nb_sales = $nb_sales;

    $sql_cu = DB::table('tj_requete')
    ->crossJoin('tj_user_app')
    ->crossJoin('tj_conducteur')
    ->select('montant as cu')
    ->where('tj_requete.statut','=','completed')
    ->where('tj_requete.id_user_app','=',DB::raw('tj_user_app.id'))
    ->where('tj_requete.id_conducteur','=',DB::raw('tj_conducteur.id'))
    ->where('tj_requete.id_conducteur','=',$id_diver)
    ->orderBy('tj_requete.id','desc')
    ->get();
    $earning = 0;

    $sql_com = DB::table('tj_commission')
    ->select('value')
    ->where('type','=','Percentage')
    ->where('statut','=','yes')
    ->orderBy('id','desc')
    ->get();
    if(!empty($sql_com)){
        foreach($sql_com as $row_com){

            $value = $row_com->value;
            $value = 1-(float)($value);
        }

         // output data of each row
         $value_fixed = 0;
         $sql_com_fixed = DB::table('tj_commission')
                        ->select('value')
                        ->where('type','=','Fixed')
                        ->where('statut','=','yes')
                        ->orderBy('id','desc')
                        ->get();
         if(!empty( $sql_com_fixed)){
            foreach($sql_com_fixed as $row_com_fixed){
             $value_fixed = $row_com_fixed->value;
            }
         }

         foreach($sql_cu as $row_cu){
             $cu = $row_cu->cu;
             $cu = $cu * $value;
             $earning = (Float)$earning + ((Float)$cu - (Float)$value_fixed);
         }
    }else{
        $sql_com = DB::table('tj_commission')
        ->select('value')
        ->where('type','=','Fixed')
        ->where('statut','=','yes')
        ->orderBy('id','desc')
        ->get();
        if(!empty($sql_com)){

            // output data of each row
            $value_fixed = 0;
           $sql_com_fixed = DB::table('tj_commission')
           ->select('value')
           ->where('type','=','Fixed')
           ->where('statut','=','yes')
           ->orderBy('id','desc')
           ->get();
            if(!empty($sql_com_fixed)){
                foreach($sql_com_fixed as $row_com_fixed){
                $value_fixed = $row_com_fixed->value;
                }
            }

            foreach($sql_cu as $row_cu){
                $cu = $row_cu->cu;
                $earning = (Float)$earning + ((Float)$cu - (Float)$value_fixed);
            }
        }else{

        }
    }
        if($earning < 0)
        $row->today_earning = "0";
    else
        $row->today_earning = $earning;

    $sql_tip_amount = DB::table('tj_requete')
    ->select(DB::raw("SUM(tip_amount) as tip_amount"))
    ->where('id_conducteur','=',$id_diver)
    ->get();
    if(!empty($sql_tip_amount)){
        foreach($sql_tip_amount as $row_tip_amount)
      {
        $tip_amount = $row_tip_amount->tip_amount;
      }
    }else{
        $tip_amount = 0;
    }
    $row->tip_amount = $tip_amount;

   // $output[] = $row;

if(!empty($sql)){
    $response['success']= 'Success';
    $response['error']= null;
    $response['data'] = $row;

}else{
    $response['success']= 'Failed';
    $response['error']= 'Failed to load data';
}
return response()->json($response);
  }

}
