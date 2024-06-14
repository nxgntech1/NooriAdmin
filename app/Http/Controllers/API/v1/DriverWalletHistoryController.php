<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\UserApp;
use App\Models\Note;
use Illuminate\Http\Request;
use DB;
class DriverWalletHistoryController extends Controller
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
    $daily_ride= [];
    $monthly_ride=[];
    $yearly_ride=[];
    $weekly_ride=[];
    $id_diver =$request->get('id_diver');
    $date_start = date('Y-m-d 00:00:00');
    $date_end = date('Y-m-d 23:59:59');
    $date_before_week = date('Y-m-d 00:00:00', strtotime('-7 days'));
    $month = date('m');
    $year = date('Y');
    $output=[];
    $wallet=[];
    if(!empty($id_diver)){

    $sql_total_earning = DB::table('tj_conducteur')
    ->select('amount')
    ->where('id','=',$id_diver)
    ->first();

    $total_earning = strval($sql_total_earning->amount);

    $sql = DB::table('tj_conducteur_transaction')
    ->join('tj_requete','tj_requete.id','=','tj_conducteur_transaction.id_ride')
    ->Join('tj_payment_method','tj_requete.id_payment_method','=','tj_payment_method.id')
    ->leftJoin('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
    ->Join('tj_conducteur', 'tj_conducteur.id', '=', 'tj_requete.id_conducteur')
    ->select(       'tj_requete.id',
                    'tj_requete.id_user_app',
                    'tj_requete.distance_unit',
                    'tj_requete.depart_name',
                    'tj_requete.destination_name',
                    'tj_requete.otp',
                    'tj_requete.latitude_depart',
                    'tj_requete.longitude_depart',
                    'tj_requete.latitude_arrivee',
                    'tj_requete.longitude_arrivee',
                    'tj_requete.number_poeple',
                    'tj_requete.place',
                    'tj_requete.statut',
                    'tj_requete.id_conducteur',
                    'tj_requete.creer',
                    'tj_requete.trajet',
                    'tj_requete.feel_safe_driver',
                    'tj_user_app.nom',
                    'tj_user_app.prenom',
                    'tj_user_app.id as existing_user_id',
                    'tj_requete.distance',
                    'tj_requete.ride_type',
                    'tj_user_app.phone',
                    'tj_user_app.photo_path',
                    'tj_conducteur.nom as nomConducteur',
                    'tj_conducteur.prenom as prenomConducteur',
                    'tj_conducteur.phone as driverPhone',
                    'tj_requete.date_retour',
                    'tj_requete.heure_retour',
                    'tj_requete.statut_round',
                    'tj_requete.montant',
                    'tj_requete.duree',
                    'tj_user_app.id as userId',
                    'tj_requete.statut_paiement',
                    'tj_payment_method.libelle as payment',
                    'tj_payment_method.image as payment_image',
                    'tj_requete.trip_objective',
                    'tj_requete.age_children1',
                    'tj_requete.age_children2',
                    'tj_requete.age_children3',
                    'tj_requete.stops',
                    'tj_requete.tax',
                    'tj_requete.tip_amount',
                    'tj_requete.discount',
                    'tj_requete.admin_commission',
                    'tj_requete.user_info',
                    'tj_conducteur_transaction.amount')
    ->where('tj_requete.statut','=','completed')
    ->where('tj_requete.statut_paiement','=','yes')
    ->where('tj_requete.id_conducteur','=',$id_diver)
    ->orderBy('tj_requete.creer','desc')
    ->get();

    foreach($sql as $row)
    {
        $row->userId = (string)$row->userId;
        $row->discount = $row->discount;
        $row->tip_amount = $row->tip_amount;
        $row->tax = json_decode($row->tax,true);
        $row->montant = $row->montant;
        $row->amount = (string)$row->amount;
        $row->destination_name = $row->destination_name;
        $row->depart_name = $row->depart_name;
        $row->user_info = json_decode($row->user_info, true);
        $row->stops = json_decode($row->stops, true);
        if($row->ride_type==null || $row->ride_type==""){
                    $row->ride_type = "normal";
        }
        $row->creer = date("d", strtotime($row->creer))." ".$months[date("F", strtotime($row->creer))].", ".date("Y", strtotime($row->creer));
        $row->date_retour = date("d", strtotime($row->date_retour))." ".$months[date("F", strtotime($row->date_retour))].", ".date("Y", strtotime($row->date_retour));

        $row->id=(string)$row->id;
        // Nb confirmed
                if ($row->photo_path != '') {
                    if (file_exists(public_path('assets/images/users' . '/' . $row->photo_path))) {
                        $image_user = asset('assets/images/users') . '/' . $row->photo_path;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->photo_path = $image_user;
                }
                $moyenne_driver = 0;

                if(!empty($row->existing_user_id)){
                    $sql_nb_avis_driver = DB::table('tj_user_note')
                        ->select(DB::raw("COUNT(id) as nb_avis_driver"), DB::raw("SUM(niveau_driver) as somme_driver"))
                        ->where('id_user_app', '=', $row->existing_user_id)
                        ->get();
                    if (!empty($sql_nb_avis_driver)) {
                        foreach ($sql_nb_avis_driver as $row_nb_avis_driver)
                            $somme_driver = $row_nb_avis_driver->somme_driver;
                        $nb_avis_driver = $row_nb_avis_driver->nb_avis_driver;
                        if ($nb_avis_driver != 0) {
                            $moyenne_driver = $somme_driver / $nb_avis_driver;

                        } else {
                            $moyenne_driver = 0;
                        }
                    } else {
                        $somme_driver = 0;
                        $nb_avis_driver = 0;
                        $moyenne_driver = 0;
                    }

                
            }
                $sql_nb_avis = DB::table('tj_note')
                    ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                    ->where('id_conducteur', '=', $row->id_conducteur)
                    ->get();

                if (!empty($sql_nb_avis)) {
                    foreach ($sql_nb_avis as $row_nb_avis)
                        $somme = $row_nb_avis->somme;
                    $nb_avis = $row_nb_avis->nb_avis;
                    if ($nb_avis != 0)
                        $moyenne = $somme / $nb_avis;
                    else
                        $moyenne = 0;
                } else {
                    $somme = 0;
                    $nb_avis = 0;
                    $moyenne = 0;
                }
                $row->moyenne_driver = (string)$moyenne_driver;
                $row->moyenne = (string)$moyenne;
                $row->order_type = 'ride';
                $row->existing_user_id = (string)$row->existing_user_id;
                $output[] = $row;

    

    }
    $parcelOrder = DB::table('tj_conducteur_transaction')
    ->join('parcel_orders','parcel_orders.id','=','tj_conducteur_transaction.id_parcel')
    ->Join('tj_payment_method','parcel_orders.id_payment_method','=','tj_payment_method.id')
    ->leftJoin('tj_user_app', 'tj_user_app.id', '=', 'parcel_orders.id_user_app')
    ->Join('tj_conducteur', 'tj_conducteur.id', '=', 'parcel_orders.id_conducteur')
    ->select(       'parcel_orders.*',
                    'tj_user_app.nom',
                    'tj_user_app.prenom',
                    'tj_user_app.phone',
                    'tj_user_app.photo_path',
                    'tj_conducteur.nom as nomConducteur',
                    'tj_conducteur.prenom as prenomConducteur',
                    'tj_conducteur.phone as driverPhone',
                    'tj_user_app.id as userId',
                    'tj_payment_method.libelle as payment',
                    'tj_payment_method.image as payment_image',
                    'tj_conducteur_transaction.amount as transactionAmount')
    ->where('parcel_orders.status','=','completed')
    ->where('parcel_orders.payment_status','=','yes')
    ->where('parcel_orders.id_conducteur','=',$id_diver)
    ->orderBy('parcel_orders.created_at','desc')
    ->get();

    if(!empty($parcelOrder)){
        foreach($parcelOrder as $po){
        $po->id=(string)$po->id;
        $po->userId = (string)$po->userId;
        $po->discount = $po->discount;
        $po->tip = $po->tip;
        $po->tax = json_decode($po->tax,true);
        $po->amount = $po->amount;
        $po->transactionAmount = (string)$po->transactionAmount;
        $po->destination = $po->destination;
        $po->source = $po->source;
        $po->order_type = 'parcel';
        $po->created_at = date("d", strtotime($po->created_at))." ".$months[date("F", strtotime($po->created_at))].", ".date("Y", strtotime($po->created_at));
        // Nb confirmed
                if ($po->photo_path != '') {
                    if (file_exists(public_path('assets/images/users' . '/' . $po->photo_path))) {
                        $image_user = asset('assets/images/users') . '/' . $po->photo_path;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');
                    }
                    $po->photo_path = $image_user;
                }
                    $image_parcel = [];
                    if ($po->parcel_image != '') {
                        $parcelImage = json_decode($po->parcel_image, true);

                        foreach ($parcelImage as $value) {
                            if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                                $image = asset('images/parcel_order/') . '/' . $value;
                            }
                            array_push($image_parcel, $image);
                        }
                        if (!empty($image_parcel)) {
                            $po->parcel_image = $image_parcel;
                        } else {
                            $po->parcel_image = asset('assets/images/placeholder_image.jpg');
                        }
                    }

                    $moyenne_driver = 0;
                $sql_nb_avis = DB::table('tj_note')
                    ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                    ->where('id_conducteur', '=', $po->id_conducteur)
                    ->get();

                if (!empty($sql_nb_avis)) {
                    foreach ($sql_nb_avis as $row_nb_avis)
                        $somme = $row_nb_avis->somme;
                    $nb_avis = $row_nb_avis->nb_avis;
                    if ($nb_avis != 0)
                        $moyenne = $somme / $nb_avis;
                    else
                        $moyenne = 0;
                } else {
                    $somme = 0;
                    $nb_avis = 0;
                    $moyenne = 0;
                }
                $po->moyenne_driver = (string)$moyenne_driver;
                $po->moyenne = (string)$moyenne;

                $output[] = $po;

        }
    }
    $wallet_transaction = DB::table('tj_conducteur_transaction')
    ->select('tj_conducteur_transaction.payment_method as libelle',
            'tj_conducteur_transaction.amount','tj_conducteur_transaction.creer','tj_conducteur_transaction.id')
    ->where('tj_conducteur_transaction.id_conducteur','=',$id_diver)
    ->where('tj_conducteur_transaction.id_ride','=',null)
    ->where('tj_conducteur_transaction.id_parcel','=',null)
    ->orderBy('tj_conducteur_transaction.creer','desc')
    ->get();

    if(!empty($wallet_transaction)){
        foreach($wallet_transaction as $wt){

            $wt->id=(string)$wt->id;
            $wt->amount=$wt->amount;
            $wt->id_payment_method="";
            $wt->libelle=$wt->libelle;
            $wt->creer=date("d", strtotime($wt->creer))." ".$months[date("F", strtotime($wt->creer))].", ".date("Y", strtotime($wt->creer));;
            $wt->user_name = "";
            $wt->user_photo = "";
            $wt->user_photo_path = "";
            $wt->destination_name="";
            $wt->depart_name="";
            $wt->id_user_app="";
            $wt->admin_commission="";
            $wt->discount="";
            $wt->tip_amount="";
            $wt->tax="";
            $wt->montant="";

            $output[]=$wt;
        }

    }
    if(!empty($output)){
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'Successfully';
        $response['data'] = $output;
        $response['total_earnings'] = $total_earning;


    }else{
        $response['success'] = 'Failed';
        $response['error'] = null;
        $response['message'] = 'No Data Found';
        }
}else{
    $response['success'] = 'Failed';
    $response['error'] = 'Id is required';

}
        return response()->json($response);

    }



}
