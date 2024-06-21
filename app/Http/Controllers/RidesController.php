<?php

namespace App\Http\Controllers;
use App\Models\Complaints;
use App\Models\UserNote;
use App\Models\Note;
use App\Models\Currency;
use App\Models\Requests;
use App\Models\Rides;
use App\Models\Driver;
use App\Models\Zone;
use App\Models\UserApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\API\v1\NotificationsController;

class RidesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all(Request $request,$id=null)
    {

      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');

              $rides = Requests::query()
                  ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                  ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                  ->select('tj_requete.id', 'tj_requete.statut','tj_requete.ride_type','tj_requete.dispatcher_id','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant','tj_requete.user_info' ,'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                  ->whereDate('tj_requete.creer', '>=', $fromDate)
                  ->whereDate('tj_requete.creer', '<=', $toDate)
                  ->where('tj_requete.deleted_at', '=', NULL);

                 $rides=$rides->orderBy('tj_requete.id','desc')->paginate(20);

        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');

                $rides = DB::table('tj_requete')
                    ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                    ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                    ->select('tj_requete.id', 'tj_requete.ride_type','tj_requete.dispatcher_id','tj_requete.user_info','tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                    ->whereDate('tj_requete.creer', '>=', $fromDate)
                    ->where('tj_requete.deleted_at', '=', NULL);

               $rides = $rides->orderBy('tj_requete.id', 'desc')->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');

              $rides = DB::table('tj_requete')
                  ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                  ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                  ->select('tj_requete.id','tj_requete.ride_type','tj_requete.dispatcher_id' ,'tj_requete.user_info','tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                  ->whereDate('tj_requete.creer', '<=', $toDate)
                  ->where('tj_requete.deleted_at', '=', NULL);

            $rides = $rides->orderBy('tj_requete.id', 'desc')->paginate(20);
        } else if ($request->selected_search == 'userName' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            //$searchs = explode(" ", $search);
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.ride_type', 'tj_requete.dispatcher_id', 'tj_requete.user_info', 'tj_requete.tip_amount', 'tj_requete.admin_commission', 'tj_requete.tax', 'tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image');
                  if($id!='' || $id!=null){
                    $rides->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%')->where('tj_requete.id_conducteur','=',$id);
                    $rides->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')->where('tj_requete.id_conducteur','=',$id);
                    $rides->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%')->where('tj_requete.id_conducteur','=',$id);
                  }
                  else{
                    $rides->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%');
                    $rides->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%');
                    $rides->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');

                  }
                  $rides->where('tj_requete.deleted_at', '=', NULL);

            $rides = $rides->orderBy('tj_requete.id', 'desc')->paginate(20);
        }
        else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');

              $rides = DB::table('tj_requete')
                  ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                  ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                  ->select('tj_requete.id', 'tj_requete.statut','tj_requete.ride_type','tj_requete.dispatcher_id','tj_requete.user_info','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                  ->where('tj_requete.statut', 'LIKE', '%' . $search . '%')
                  ->where('tj_requete.deleted_at', '=', NULL);

            $rides = $rides->orderBy('tj_requete.id', 'desc')->paginate(20);
        }
        // else if ($request->selected_search == 'type' && $request->has('ride_type') && $request->ride_type != '') {
        //     $search = $request->input('ride_type');

        //     $query = DB::table('tj_requete')
        //         ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
        //         ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
        //         ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.ride_type', 'tj_requete.dispatcher_id', 'tj_requete.user_info', 'tj_requete.tip_amount', 'tj_requete.admin_commission', 'tj_requete.tax', 'tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image');
        //          // ->where('tj_requete.deleted_at', '=', NULL);
        //           if($id!='' || $id!=null){
        //             $query->where('tj_requete.id_conducteur','=',$id);
        //           }
        //           if($search == "dispatcher"){
        //             $query->where('tj_requete.ride_type','dispatcher');
        //              } elseif ($search == "driver_created") {
        //               $query->where('tj_requete.ride_type', 'driver');

        //             }else{
        //             $query->where('tj_requete.ride_type',NULL);
        //           }

        //     $rides = $query->orderBy('tj_requete.id', 'desc')->paginate(20);
        // }
         else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.ride_type','tj_requete.dispatcher_id','tj_requete.user_info','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->where('tj_requete.deleted_at', '=', NULL)
                ->where('tj_requete.statut', '!=', NULL);
                
            $rides = $rides->orderBy('tj_requete.id', 'desc')->paginate(20);

        }

        return view("rides.all")->with("rides", $rides)->with('currency', $currency)->with('id',$id);
    }

    public function new(Request $request)
    {
      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_user_app.id as id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
               ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        // else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {

        //     $search = $request->input('search');
        //     $rides = DB::table('tj_requete')
        //         ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
        //         ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        //         ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
        //         ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_user_app.id as id_user_app','tj_requete.id_conducteur')
        //         ->orderBy('tj_requete.creer', 'DESC')
        //         ->where('tj_requete.statut', 'new')
        //         ->where(function ($query) use ($search) {
        //             $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
        //                 ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
        //                 ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');
        //         })
        //        ->where('tj_requete.deleted_at', '=', NULL)
        //         ->paginate(20);
        //     }
            else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
                $search = $request->input('ride_status');
                $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_user_app.id as id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%');
                })
               ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
                }
            else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount','tj_requete.id_user_app','tj_requete.id_conducteur','tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'new')
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }

        return view("rides.new")->with("rides", $rides)->with('currency', $currency);
    }

    public function confirmed(Request $request)
    {
      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }else if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%');

                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.id_user_app','tj_requete.id_conducteur','tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'confirmed')
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }

        return view("rides.confirmed")->with("rides", $rides)->with('currency', $currency);
    }


    public function onRide(Request $request)
    {

      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }else if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                       ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                       ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%');

                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.id_user_app','tj_requete.id_conducteur','tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'on ride')
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        return view("rides.onride")->with("rides", $rides)->with('currency', $currency);
    }

    public function rejected(Request $request)
    {
      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount','tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        //->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%')
                        //->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        //->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        //->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        //->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        //->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        //->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');
                        //->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app','tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%');

                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.id_user_app','tj_requete.id_conducteur','tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->Where(function ($query) {
                    $query->where('tj_requete.statut', 'rejected')
                        ->orwhere('tj_requete.statut', 'canceled');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }
        return view("rides.rejected")->with("rides", $rides)->with('currency', $currency);
    }

    public function completed(Request $request)
    {
      $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('datepicker_from') && $request->datepicker_from != '' && $request->has('datepicker_to') && $request->datepicker_to != '') {
            $fromDate = $request->input('datepicker_from');
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'completed')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_from') && $request->datepicker_from != '') {
            $fromDate = $request->input('datepicker_from');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'completed')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->has('datepicker_to') && $request->datepicker_to != '') {
            $toDate = $request->input('datepicker_to');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'completed')
                ->whereDate('tj_requete.creer', '<=', $toDate)
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        } else if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app', 'tj_requete.id_conducteur')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'completed')
                ->where(function ($query) use ($search) {
                    $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);
        }else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = DB::table('tj_requete')
            ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
            ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app', 'tj_requete.id_conducteur')
            ->orderBy('tj_requete.creer', 'DESC')
            ->where('tj_requete.statut', 'completed')
            ->where(function ($query) use ($search) {
                $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                    ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

            })
            ->where('tj_requete.deleted_at', '=', NULL)
            ->paginate(20);
        }
        else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = DB::table('tj_requete')
            ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
            ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.id_user_app', 'tj_requete.id_conducteur')
            ->orderBy('tj_requete.creer', 'DESC')
            ->where('tj_requete.statut', 'completed')
            ->where(function ($query) use ($search) {
                $query->where('tj_requete.depart_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tj_requete.destination_name', 'LIKE', '%' . $search . '%')
                    ->orwhere('tj_requete.statut', 'LIKE', '%' . $search . '%');

            })
            ->where('tj_requete.deleted_at', '=', NULL)
            ->paginate(20);
        }
         else {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.id_user_app', 'tj_requete.id_conducteur', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
                ->orderBy('tj_requete.creer', 'DESC')
                ->where('tj_requete.statut', 'completed')
                ->where('tj_requete.deleted_at', '=', NULL)
                ->paginate(20);

        }
        return view("rides.completed")->with("rides", $rides)->with('currency', $currency);
    }

    public function filterRides(Request $request)
    {
        $page = $request->input('pageName');
        $fromDate = $request->input('datepicker-from');
        $toDate = $request->input('datepicker-to');

        if ($page == "allpage") {
            $rides = DB::table('tj_requete')
                ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
                ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle')
                ->orderBy('tj_requete.id', 'DESC')
                ->whereDate('tj_requete.creer', '>=', $fromDate)
                ->paginate(10);
            return view("rides.all")->with("rides", $rides);
        } else {

        }

    }

    public function deleteRide($id)
    {

        if ($id != "") {

            $id = json_decode($id);


            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $complaint = Complaints::where('id_ride', $id[$i]);
                    if ($complaint) {
                        $complaint->delete();
                    }
                    $Note = Note::where('ride_id', $id[$i]);
                    if ($Note) {
                        $Note->delete();
                    }
                    $userNote = UserNote::where('ride_id', $id[$i]);
                    if ($userNote) {
                        $userNote->delete();
                    }

                    $user = Requests::find($id[$i]);
                    $user->delete();
                }

            } else {
                $complaint = Complaints::where('id_ride', $id);
                if ($complaint) {
                    $complaint->delete();
                }
                $Note = Note::where('ride_id', $id);
                if ($Note) {
                    $Note->delete();
                }
                $userNote = UserNote::where('ride_id', $id);
                if ($userNote) {
                    $userNote->delete();
                }

                $user = Requests::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

     public function show($id)
     {
        $currency = Currency::where('statut', 'yes')->first();
        $selectedride = Requests::where('id',$id)->first();
        if($selectedride->statut =='new')
        {
            $ride = Requests::leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
             ->join('bookingtypes', 'tj_requete.booking_type_id', '=', 'bookingtypes.id' )
             ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
             ->leftjoin('car_model', 'tj_requete.model_id', '=', 'car_model.id')
             ->leftjoin('brands', 'car_model.brand_id', '=', 'brands.id')
             ->select('tj_requete.*')
             ->addSelect('tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.email as user_email','tj_user_app.photo_path')
             ->addSelect('tj_payment_method.libelle', 'tj_payment_method.image')
             ->addSelect('brands.name as brand', 'car_model.name as model','bookingtypes.bookingtype as booking_type')
             ->where('tj_requete.id', $id)->first();
        }
        else
        {
         $ride = Requests::leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
             ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
             ->join('bookingtypes', 'tj_requete.booking_type_id', '=', 'bookingtypes.id' )
             ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
             ->join('tj_vehicule', 'tj_requete.vehicle_id', '=', 'tj_vehicule.id')
             ->leftjoin('brands', 'tj_vehicule.brand', '=', 'brands.id')
             ->leftjoin('car_model', 'tj_vehicule.model', '=', 'car_model.id')
             ->select('tj_requete.*')
             ->addSelect('tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.email as driver_email', 'tj_conducteur.photo_path as driver_photo')
             ->addSelect('tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.email as user_email','tj_user_app.photo_path')
             ->addSelect('tj_payment_method.libelle', 'tj_payment_method.image')
             ->addSelect('tj_vehicule.brand', 'tj_vehicule.model', 'tj_vehicule.car_make', 'tj_vehicule.numberplate', 'brands.name as brand', 'car_model.name as model','bookingtypes.bookingtype as booking_type')
             ->where('tj_requete.id', $id)->first();
        }
        //echo json_encode($ride,JSON_PRETTY_PRINT);

         $id_conducteur = $ride->id_conducteur ?? 0;
         $montant = $ride->montant;
         $tax = json_decode($ride->tax,true);
         $discount = $ride->discount;
         $tip = $ride->tip_amount;
         $totalAmount = floatval($montant);
         $totalTaxAmount = 0;
         $taxHtml = '';
         if (!empty($tax)) {
             for ($i = 0; $i < sizeof($tax); $i++) {
                 $data = $tax[$i];
                 if ($data['type'] == "Percentage") {
                     $taxValue = (floatval($data['value']) * $totalAmount) / 100;
                     $taxlabel = $data['libelle'];
                     $value = $data['value']."%";
                 } else {
                     $taxValue = floatval($data['value']);
                     $taxlabel = $data['libelle'];
                     if ($currency->symbol_at_right == "true") {
                         $value = number_format($data['value'],$currency->decimal_digit) . "" . $currency->symbole;
                     } else {
                         $value = $currency->symbole."".number_format($data['value'],$currency->decimal_digit);
                     }
                 }
                 $totalTaxAmount += floatval(number_format($taxValue,$currency->decimal_digit));
                 if ($currency->symbol_at_right == "true") {
                     $taxValueAmount = number_format($taxValue,$currency->decimal_digit) . "" . $currency->symbole;
                 } else {
                     $taxValueAmount = $currency->symbole . "" . number_format($taxValue,$currency->decimal_digit);
                 }
                 $taxHtml = $taxHtml."<tr><td class='label'>" . $taxlabel . "(" . $value . ")</td><td><span style='color:green'>+" . $taxValueAmount . "<span></td></tr>";
             }
            $totalAmount = floatval($totalAmount) ;

        }
             $totalAmount = floatval($totalAmount);
             $customer_review = DB::table('tj_note')->where('tj_note.ride_id', $id)->select('comment','niveau')->get();
             $driver_review = DB::table('tj_user_note')->where('tj_user_note.ride_id', $id)->select('comment','niveau_driver')->get();

        $driverRating = "0.0";

        $driver_rating = DB::table('tj_note')
            ->select(DB::raw("COUNT(id) as ratingCount"), DB::raw("SUM(niveau) as ratingSum"))
            ->where('id_conducteur', '=', $id_conducteur)
            ->first();
            if(!empty($driver_rating)){
                if($driver_rating->ratingCount>0){
                     $driverRating = number_format(($driver_rating->ratingSum / $driver_rating->ratingCount),1);
                }
            }
        $userRating = "0.0";

        if (!empty($ride->id_user_app)) {
            $id_user = $ride->id_user_app;
            $user_rating = DB::table('tj_user_note')
                ->select(DB::raw("COUNT(id) as ratingCount"), DB::raw("SUM(niveau_driver) as ratingSum"))
                ->where('id_user_app', '=', $id_user)
                ->first();
            if (!empty($user_rating)) {
                if ($user_rating->ratingCount > 0) {
                    $userRating = number_format(($user_rating->ratingSum / $user_rating->ratingCount));
                }
            }

        }
        $complaints = Complaints::select('title', 'description','user_type')->where('id_ride', $id)->get();
        //echo json_encode($ride,JSON_PRETTY_PRINT);
        $ride->zone_name = '';
        if($ride->id_conducteur && $ride->id_conducteur !=0){
            $zone_name = '';
            $driver = Driver::find($ride->id_conducteur);
            $zone_id = explode(',',$driver->zone_id);
            $zones = Zone::whereIn('id',$zone_id)->get();
                foreach($zones as $zone){
                $zone_name .= $zone->name.', ';
            }
            $ride->zone_name = rtrim($zone_name,', ');
        }

       // $drivers = DB::table('tj_conducteur')->where('statut','yes')->where('online','yes')->select('id','nom', 'prenom')->get();
        //$vehicles = DB::table('tj_vehicule')->where('statut','yes')->where('deleted_at',null)->get();
        // Define the subquery for booked rides
        $subQuery = DB::table('tj_requete as A')
            ->join('tj_requete as B', 'A.id', '=', 'B.id')
            ->select(DB::raw('IFNULL(A.id_conducteur, 0) as conductor_id'))
            ->whereNotIn('A.statut', ['completed', 'new'])
            ->whereRaw('date(A.ride_required_on_date) != date(B.ride_required_on_date)')
            ->where('A.id', $id);

        // Main query to get available conductors
        $drivers = DB::table('tj_conducteur as A')
            ->leftJoinSub($subQuery, 'booked_rides', function ($join) {
                $join->on('A.id', '=', 'booked_rides.conductor_id');
            })
            ->whereNull('booked_rides.conductor_id')
            ->select('A.id','A.nom','A.prenom')
            ->get();



        $vehicles =DB::table('tj_vehicule as A')
        ->join('tj_requete as B', 'A.MODEL', '=', 'B.MODEL_ID')
        ->where('B.id', $id)
        ->whereNotIn('A.id', function($query) {
            $query->select(DB::raw('IFNULL(vehicle_id, 0)'))
                ->from('tj_requete')
                ->whereNotIn('statut', ['completed', 'new'])
                ->whereRaw('date(ride_required_on_date) != date(B.ride_required_on_date)')
                ->whereColumn('MODEL_ID', 'A.MODEL');
        })
        ->select('A.*') // Select columns from both tables as per your requirement
        ->get();
        $localTime = Carbon::parse($ride->creer)->timezone('Asia/Kolkata');
        $msg="This is testing";
        //echo json_encode($vehicles,JSON_PRETTY_PRINT);
        return view("rides.show")->with("ride", $ride)->with("currency", $currency)
                 ->with("customer_review", $customer_review)
                 ->with("driver_review", $driver_review)
                 ->with("complaints", $complaints)
                 ->with('taxHtml', $taxHtml)
                 ->with('totalAmount', $totalAmount)
                 ->with('driverRating',$driverRating)
                 ->with('userRating',$userRating)->with('drivers',$drivers)->with('vehicles',$vehicles)->with('localTime',$localTime);
         
    }

    public function updateRide(Request $request, $id)
    {

        $rides = Rides::find($id);
        $driver = $request->input('order_status');
        $vehicleid = $request->input('selectedvehicleid');

        if ($rides) {

            // $rides->statut = "vehicle assigned";
            // $rides->id_conducteur= $driver;
            // $rides->vehicle_Id=$vehicleid;
            // $rides->save();

             $driverinfo = Driver::where('id',$driver)->where('fcm_id', '!=', '')->first();

             

            // $usermsg = "Congratulations your booking got confirmed with the Driver :".$driverinfo->nom ." ".$driverinfo->prenom.", mobile: ".$driverinfo->phone."";
            // $usertitle = "Booking Confirmed";
            // $messages = array("body" => $usermsg, "title" => $usertitle, "sound" => "default", "tag" => "notification");

            // $users = UserApp::where('id',$rides->id_user_app)-> where('fcm_id', '!=', '')->first();

            // $tokens = $insert_data = array();
            // $temp = array();
            // array_push($tokens,$users->fcm_id);
            // //Cosumer notification
            // GcmController::send_notification($tokens, $messages,$temp);

            // $drivermsg = "You have got a booking with the Customer :".$users->nom ." ".$driverinfo->prenom.", mobile: ".$driverinfo->phone."";
            // $drivertitle = "Booking Assigned";
            
            // $drivermessages = array("body" => $drivermsg, "title" => $drivertitle, "sound" => "default", "tag" => "notification");

            // $tokens = $insert_data = array();
            // array_push($tokens,$driverinfo->fcm_id);

            // GcmController::send_notification($tokens, $drivermessages,$temp);

             $msg = 'Notification successfully sent';


            $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');
            $sql = DB::table('tj_requete')
            ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
            ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
            ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
            ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            ->Join('bookingtypes', 'tj_requete.booking_type_id','=','bookingtypes.id')
            ->join('tj_vehicule','tj_requete.vehicle_Id','=','tj_vehicule.id')
            ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
                'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
                'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
                'tj_requete.statut', 'tj_requete.id_conducteur',
                'tj_requete.creer', 'tj_requete.tax_amount','tj_requete.discount',
                'tj_user_app.nom', 'tj_user_app.fcm_id','tj_user_app.prenom', 'tj_requete.otp','tj_user_app.email as customeremail','tj_user_app.phone as customerphone',
                'tj_requete.distance', 'tj_user_app.phone','tj_requete.date_retour', 'tj_requete.heure_retour',
                'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
                'tj_requete.car_Price','tj_requete.sub_total',
                'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time','tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
                'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
                'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image','tj_requete.id_payment_method as paymentmethodid', 'bookingtypes.bookingtype as bookingtype',
                'tj_vehicule.numberplate')
                ->where('tj_requete.id', '=', $id)
                ->get();
            foreach ($sql as $row) {
                $customer_name = $row->prenom.' '.$row->nom;
                $customerphone = $row->customerphone;
                $carmodelandbrand = $row->brandname .' / '. $row->carmodel;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $booking_date = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
                $booking_time = date("h:m A", strtotime($row->creer)); 
                $payment_method = $row->payment;
                $bookingtype = $row->bookingtype;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:m A", strtotime($row->ride_required_on_time));
                $brandname = $row->brandname;
                $numberplate = $row->numberplate;
             }
            // admin email
            
            $emailsubject = '';
            $emailmessage = '';

            $emailsubject = "Driver assigned!";
            $emailmessage = file_get_contents(resource_path('views/emailtemplates/customer_driver_assigned.html'));

            $drivername = $driverinfo->prenom.' '.$driverinfo->nom;
            $driverphone = $driverinfo->phone;

            $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
            $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
            $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
            $emailmessage = str_replace("{DriverName}", $drivername, $emailmessage);
            $emailmessage = str_replace("{BrandName}", $brandname, $emailmessage);
            $emailmessage = str_replace("{carmodel}", $carmodelandbrand, $emailmessage);
            
            $emailmessage = str_replace("{CarNumber}", $numberplate, $emailmessage);
            $emailmessage = str_replace("{DriverNumber}", $driverphone, $emailmessage);

            $admintoemail=env('ADMIN_EMAILID','govind.p.raj@gmail.com');
            $notifications= new NotificationsController();
            $response['CustomerEmailResponse'] = $notifications->sendEmail($admintoemail, $emailsubject,$emailmessage);
             // driver email
            $emailsubject = '';
            $emailmessage = '';

            $emailsubject = "New ride assigned!";
            $emailmessage = file_get_contents(resource_path('views/emailtemplates/to_driver_assign-driver.html'));

            $drivername = $driverinfo->prenom.' '.$driverinfo->nom;
            

            $emailmessage = str_replace("{DriverName}", $drivername, $emailmessage);
            $emailmessage = str_replace("{carmodel}", $carmodelandbrand, $emailmessage);
            $emailmessage = str_replace("{CarNumber}", $numberplate, $emailmessage);
            $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
            $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
            $emailmessage = str_replace("{PickupDate}", $pickupdate, $emailmessage);
            $emailmessage = str_replace("{PickupTime}", $pickuptime, $emailmessage);
            $emailmessage = str_replace("{BookingType}", $bookingtype, $emailmessage);
            $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
            $emailmessage = str_replace("{BrandName}", $brandname, $emailmessage);
            
            $admintoemail=env('ADMIN_EMAILID','govind.p.raj@gmail.com');
            $notifications= new NotificationsController();
            $response['DriverEmailResponse'] = $notifications->sendEmail($admintoemail, $emailsubject,$emailmessage);


            // App Notification to Consumer 
            $this->CustomerFCMNotification($id, $driverinfo, $sql);

            // App Notification to Driver 
            $this->DriverFCMNotification($id, $driverinfo, $sql);

        }

        return redirect()->back()->with('message',$msg);

    }

    public function CustomerFCMNotification($ride_id, $driverinfo, $sql)
    {
            $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

            $response['Response']="";

            $tmsg = '';
            $terrormsg = '';

            $title = "Driver Assigned";

            foreach ($sql as $row)
            {
                $carmodelandbrand = $row->brandname .' - '. $row->carmodel;
                $carnumber = $row->numberplate;
                $tokens = $row->fcm_id;
            }
            
            $msg = str_replace("{carmodel}", $carmodelandbrand, "{carmodel} Reg. no. {carnumber} has been assigned with driver {DriverName} for your ride. The driver can be reachable on {DriverNumber}");
            $msg = str_replace("{carnumber}", $carnumber, $msg);
            $msg = str_replace("{DriverName}", $driverinfo->nom, $msg);
            $msg = str_replace("{DriverNumber}", $driverinfo->phone, $msg);
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'ridenewrider'
            ];

            if (!empty($tokens)){
                $notifications= new NotificationsController();
                $response['Response'] = $notifications->sendNotification($tokens, $message, $data);
            }

            return response()->json($response);
    }

    public function DriverFCMNotification($ride_id, $driverinfo, $sql)
    {

            $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');
            $response['Response']="";

            $tmsg = '';
            $terrormsg = '';

            $title = "New Ride Assigned";

            // {carmodel} {carnumber} {PickupLocation} {DropoffLocation} {PickupDate} {PickupTime}
            
            foreach ($sql as $row)
            {
                $carmodelandbrand = $row->brandname .' - '. $row->carmodel;
                $carnumber = $row->numberplate;
                $PickUpLocation = $row->depart_name;
                $DropLocation = $row->destination_name;
                $PickUpDate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $PickUpTime = date("h:m A", strtotime($row->ride_required_on_time));
            }
            
            $msg = str_replace("{carmodel}", $carmodelandbrand, "You have been assigned for a new ride with {carmodel} Reg. no {carnumber} from {PickupLocation} to {DropoffLocation} on {PickupDate} at {PickupTime}");
            $msg = str_replace("{carnumber}", $carnumber, $msg);
            $msg = str_replace("{PickupLocation}", $PickUpLocation, $msg);
            $msg = str_replace("{DropoffLocation}", $DropLocation, $msg);
            $msg = str_replace("{PickupDate}", $PickUpDate, $msg);
            $msg = str_replace("{PickupTime}", $PickUpTime, $msg);
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'ridenewrider'
            ];

            $tokens = $driverinfo->fcm_id;

            if (!empty($tokens)){
                $notifications= new NotificationsController();
                $response['Response'] = $notifications->sendNotification($tokens, $message, $data);
            }

            return response()->json($response);
    }

}
