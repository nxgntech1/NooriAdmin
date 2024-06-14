<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Expression;
class taxiController extends Controller
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

    $id_user_app =  $request->get('id_user_app');

    $sql = DB::table('tj_vehicule')
    ->crossJoin('tj_conducteur')
    ->leftJoin('brands','brands.id','=','tj_vehicule.brand')
    ->leftJoin('car_model','car_model.id','=','tj_vehicule.model')
    ->select('tj_vehicule.id', 'brands.name as brand', 'car_model.name as model', 'tj_vehicule.color', 'tj_vehicule.numberplate', 'tj_vehicule.statut', 'tj_conducteur.latitude', 'tj_conducteur.longitude', 'tj_vehicule.creer', 'tj_vehicule.modifier', 'tj_conducteur.id as idConducteur', 'tj_conducteur.nom', 'tj_conducteur.prenom')
    ->where('tj_vehicule.id_conducteur','=',DB::raw('tj_conducteur.id'))
    ->where('tj_vehicule.statut','=','yes')
    ->where('tj_conducteur.online','=','yes')
    ->get();

	$output = array();
    foreach($sql as $row){


        $id_driver = $row->idConducteur;

        $sql_new = DB::table('tj_requete')
        ->select('statut')
        ->where('id_conducteur','=',DB::raw($id_driver))
        ->where('id_user_app','=',$id_user_app)
        ->orderBy('id','desc')
        ->get();

        if(!empty($sql_new)){
            foreach($sql_new as $row_new){
                if($row_new->statut == 'new')
                $row->statut_driver = 'new';
            else if($row_new->statut == 'confirmed')
                $row->statut_driver = 'confirmed';
            else
                $row->statut_driver = 'none';
            }
        }else{
            $row->statut_driver = 'none';
        }
        $row->id=(string)$row->id;
        $row->idConducteur=(string)$row->idConducteur;
        $output[] = $row;

        }
        if(!empty($sql)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully';
            $response['data'] = $output;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to fetch data';

        }
        return response()->json($response);

    }

  }
