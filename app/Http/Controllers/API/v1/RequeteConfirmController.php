<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Commission;
use Illuminate\Http\Request;
use DB;
class RequeteConfirmController extends Controller
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

    $id_driver =  $request->get('id_driver');

    if(!empty($id_driver)){
    $sql = DB::table('tj_requete')
    ->leftJoin('tj_user_app','tj_user_app.id','=','tj_requete.id_user_app')
    ->Join('tj_conducteur','tj_conducteur.id','=','tj_requete.id_conducteur')
    ->Join('tj_payment_method','tj_payment_method.id','=','tj_requete.id_payment_method')
    ->select('tj_requete.id', 'tj_requete.id_user_app','tj_requete.distance_unit', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.latitude_depart', 'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee', 'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round', 'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur', 'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.feel_safe_driver', 'tj_user_app.nom', 'tj_user_app.prenom',
    'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path',
    'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur',
    'tj_conducteur.phone as driverPhone', 'tj_requete.montant', 'tj_requete.duree',
    'tj_requete.statut_paiement', 'tj_payment_method.libelle as payment',
    'tj_payment_method.image as payment_image', 'tj_requete.car_driver_confirmed','tj_requete.stops','tj_requete.user_info')
    ->where('tj_requete.id_payment_method','=',DB::raw('tj_payment_method.id'))
    ->where('tj_requete.id_conducteur','=',$id_driver)
    ->where('tj_requete.statut','=','confirmed')
    ->where('tj_requete.id_conducteur','=',DB::raw('tj_conducteur.id'))
    ->orderBy('tj_requete.id','desc')
    ->get();

	$output = array();
    foreach($sql as $row){
        $id_user_app = $row->id_user_app;
        $row->id=(string)$row->id;
        $row->stops=json_decode($row->stops,true);
        $row->user_info = json_decode($row->user_info, true);

            if($id_user_app != 0){
            $sql_cond =DB::table('tj_conducteur')
            ->select('nom as nomConducteur', 'prenom as prenomConducteur')
            ->where('id','=',$id_driver)
            ->get();

            foreach($sql_cond as $row_cond)

            // Nb avis conducteur
            $sql_nb_avis = DB::table('tj_note')
            ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
            ->where('id_conducteur','=',$id_driver)
            ->get();

            if(!empty($sql_nb_avis)){
                foreach($sql_nb_avis as $row_nb_avis)
                $somme = $row_nb_avis->somme;
                $nb_avis = $row_nb_avis->nb_avis;
                if($nb_avis != "0")
                    $moyenne = $somme/$nb_avis;
                else
                    $moyenne = 0;
            }else{
                $somme = "0";
                $nb_avis = "0";
                $moyenne = 0;
            }
            $sql_nb_avis_driver = DB::table('tj_user_note')
            ->select(DB::raw("COUNT(id) as nb_avis_driver"), DB::raw("SUM(niveau_driver) as somme_driver"))
            ->where('id_user_app','=',$id_user_app)
            ->get();
                if(!empty($sql_nb_avis_driver)){
                    foreach($sql_nb_avis_driver as $row_nb_avis_driver)
                    $somme_driver = $row_nb_avis_driver->somme_driver;
                    $nb_avis_driver = $row_nb_avis_driver->nb_avis_driver;
                    if($nb_avis_driver != "0")
                        $moyenne_driver = $somme_driver/$nb_avis_driver;
                    else
                        $moyenne_driver = 0;
                }else{
                    $somme_driver = "0";
                    $nb_avis_driver = "0";
                    $moyenne_driver = 0;
                }

                  // Note conducteur
                  $sql_note = DB::table('tj_note')
                  ->select('niveau', 'comment')
                  ->where('id_conducteur','=',$id_driver)
                  ->where('id_user_app','=',$id_user_app)
                  ->get();
                  foreach($sql_note as $row_note)
                  {
                    if($row_note){
                        $row->comment = $row_note->comment;
                    }else{
                        $row->comment = "";
                    }
                  }
                // Note user
                $sql_note_driver = DB::table('tj_user_note')
                ->select('niveau_driver', 'comment')
                ->where('id_user_app','=',$id_user_app)
                ->where('id_conducteur','=',$id_driver)
                ->get();
                foreach($sql_note_driver as $row_note_driver){
                    if($row_note_driver){
                        $row->comment_driver = $row_note_driver->comment;
                    }else{
                        $row->comment_driver = "";
                    }
                }

                $sql_phone = DB::table('tj_conducteur')
                ->select('phone')
                ->where('id','=',$id_driver)
                ->get();
                foreach($sql_phone as $row_phone)
                {
                    $row->driver_phone = $row_phone->phone;
                }
                $row->nomConducteur = $row_cond->nomConducteur;
                $row->prenomConducteur = $row_cond->prenomConducteur;
                $row->moyenne = number_format((float)$moyenne, 1);
                $row->moyenne_driver =  number_format((float)$moyenne_driver, 1);
            }else{
                $row->nomConducteur = "";
                $row->prenomConducteur = "";
                $row->moyenne = "0.0";
                $row->driver_phone = "";
                $row->moyenne_driver = "0.0";
            }
            
            $sql_vehicle =DB::table('tj_vehicule')
            ->select('*')
            ->where('id_conducteur','=',$id_driver)
            ->get();
            foreach($sql_vehicle as $row_vehicle)
            {
                $row->idVehicule = (string)$row_vehicle->id;
                $row->brand = $row_vehicle->brand;
                $row->model = $row_vehicle->model;
                $row->car_make = $row_vehicle->car_make;
                $row->milage = $row_vehicle->milage;
                $row->km = $row_vehicle->km;
                $row->color = $row_vehicle->color;
                $row->numberplate = $row_vehicle->numberplate;
                $row->passenger = $row_vehicle->passenger;
            }
            $row->creer = date("d", strtotime($row->creer))." ".$months[date("F", strtotime($row->creer))].". ".date("Y", strtotime($row->creer));
            $row->date_retour = date("d", strtotime($row->date_retour))." ".$months[date("F", strtotime($row->date_retour))].". ".date("Y", strtotime($row->date_retour));
            if($row->photo_path != ''){
                if(file_exists(public_path('assets/images/users'.'/'.$row->photo_path )))
                {
                    $image = asset('assets/images/users').'/'. $row->photo_path;
                }
                else
                {
                    $image =asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_path = $image;
            }
            if($row->trajet != ''){
                if(file_exists(public_path('images/recu_trajet_course'.'/'.$row->trajet )))
                {
                    $image_tranjet = asset('images/recu_trajet_course').'/'. $row->trajet;
                }
                else
                {
                    $image_tranjet =asset('images/placeholder_image.jpg');

                }
                $row->trajet = $image_tranjet;
            }
            if($row->payment_image != ''){
                if(file_exists(public_path('assets/images/payment_method'.'/'.$row->payment_image )))
                {
                    $image_payment = asset('assets/images/payment_method').'/'. $row->payment_image;
                }
                else
                {
                    $image_payment =asset('assets/images/placeholder_image.jpg');

                }
                $row->payment_image = $image_payment;
            }
            $output[]= $row;
        }
        if(!empty($row)){
            $response['success']= 'success';
            $response['error']= null;
            $response['message'] = 'successfully fetched data';
            $response['data'] = $output;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'failed to fetch data';
        }
    }else{
        $response['success']= 'Failed';
        $response['error']= 'Id required';
    }

        return response()->json($response);

    }
  }
