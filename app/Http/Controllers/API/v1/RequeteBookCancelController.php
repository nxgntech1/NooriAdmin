<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\UserApp;
use Illuminate\Http\Request;
use DB;
class RequeteBookCancelController extends Controller
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

    $id_driver =$request->get('id_user_app');
  if(!empty($id_driver)){
    $sql = DB::table('tj_requete_book')
    ->crossJoin('tj_user_app')
    ->crossJoin('tj_conducteur')
    ->crossJoin('tj_payment_method')
    ->select('tj_requete_book.id', 'tj_requete_book.id_user_app', 'tj_requete_book.depart_name', 'tj_requete_book.destination_name', 'tj_requete_book.latitude_depart', 'tj_requete_book.longitude_depart', 'tj_requete_book.latitude_arrivee', 'tj_requete_book.longitude_arrivee', 'tj_requete_book.heure_retour', 'tj_requete_book.statut_round', 'tj_requete_book.number_poeple', 'tj_requete_book.place', 'tj_requete_book.statut', 'tj_requete_book.id_conducteur', 'tj_requete_book.creer', 'tj_requete_book.trajet', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete_book.distance', 'tj_user_app.phone', 'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur', 'tj_conducteur.phone as driverPhone', 'tj_requete_book.montant', 'tj_requete_book.duree', 'tj_requete_book.statut_paiement', 'tj_requete_book.date_book', 'tj_requete_book.nb_day', 'tj_requete_book.heure_depart', 'tj_requete_book.cu', 'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image')
    ->where('tj_requete_book.id_user_app','=',DB::raw('tj_user_app.id'))
    ->where('tj_requete_book.id_payment_method','=',DB::raw('tj_payment_method.id'))
    ->where('tj_requete_book.id_conducteur','=',$id_driver)
    ->where('tj_requete_book.statut','=','canceled')
    ->where('tj_requete_book.id_conducteur','=',DB::raw('tj_conducteur.id'))
    ->orderBy('tj_requete_book.heure_depart','asc')
    ->get();
    
    // output data of each row
    $output = array();
    foreach($sql as $row)
    {
        $id_user_app = $row->id_user_app;
        if($id_user_app != 0){

             // Conducteur
             $sql_cond = DB::table('tj_conducteur')
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
                    foreach($sql_nb_avis as $row_nb_avis){
                        $somme = $row_nb_avis->somme;
                        $nb_avis = $row_nb_avis->nb_avis;
                    }
                    if($nb_avis != "0")
                        $moyenne = $somme/$nb_avis;
                    else
                        $moyenne = "0";
                }else{
                    $somme = "0";
                    $nb_avis = "0";
                    $moyenne = "0";
                }

                 // Note conducteur
                 $sql_note = DB::table('tj_note')
                 ->select('niveau')
                 ->where('id_user_app','=',$id_user_app)
                 ->where('id_conducteur','=',$id_driver)
                 ->get();
                 foreach($sql_note as $row_note)
                    {
                        if(!empty($sql_note))
                            $row->niveau = $row_note->niveau;
                        else
                            $row->niveau = "";
                            $row->moyenne = $moyenne;
                                    
                    }
                    $sql_phone = DB::table('tj_conducteur')
                    ->select('phone')
                    ->where('id','=',$id_driver)
                    ->get();

                    // output data of each row
                    foreach($sql_phone as $row_phone){
                        $row->driver_phone = $row_phone->phone;
                    }

                $row->nomConducteur = $row_cond->nomConducteur;
                $row->prenomConducteur = $row_cond->prenomConducteur;
                $row->nb_avis = $row_nb_avis->nb_avis;
                
           
            
        }
        else{
            $row->nomConducteur = "";
            $row->prenomConducteur = "";
            $row->nb_avis = "";
            $row->niveau = "";
            $row->moyenne = "";
            $row->driver_phone = "";
        }
        
        $row->creer = date("d", strtotime($row->creer))." ".$months[date("F", strtotime($row->creer))].". ".date("Y", strtotime($row->creer));


        $output[] = $row;
      
    }
    if($sql->count() > 0){
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'successfully';
        $response['data'] = $output;

        }else{
            $response['success'] = 'Failed';
            $response['error'] = 'failed to fetch data';
        }
  }else{
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';
  }
        return response()->json($response);

    
        
  }
  
}