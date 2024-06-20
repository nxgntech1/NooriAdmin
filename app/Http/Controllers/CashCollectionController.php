<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pricing_by_car_models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\bookingtypes;
use App\Models\VehicleType;
use App\Models\Currency;
use Illuminate\Support\Str;

class CashCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        $pendingcollections =DB::table('tj_requete')->join('tj_conducteur','tj_requete.id_conducteur','=','tj_conducteur.id')
        ->select(DB::raw("tj_requete.id_conducteur as id, concat(tj_conducteur.nom,' ',tj_conducteur.prenom) as drivername,tj_conducteur.photo_path, count(tj_requete.id) as rides,sum(tj_requete.montant) as pendingamount"))
        ->where('tj_requete.id_payment_method', '=', '5')
        ->where('tj_requete.statut', '=', 'completed')
        ->whereNull('tj_requete.cod_collected_transaction_id')
        ->groupBy('tj_requete.id_conducteur')
        ->orderByDesc(DB::raw('sum(tj_requete.montant)'))
        ->get();
        if(!empty($pendingcollections))
        {
        $totalamount = $pendingcollections->sum('pendingamount');
        $totaltrips = $pendingcollections->sum('rides');
        }
        else{
            $totalamount = 0;
        $totaltrips = 0;
        }
        //echo json_encode($pendingcollections,JSON_PRETTY_PRINT);
        return view("cash_collection.index")->with('pendingcollections',$pendingcollections)->with('currency',$currency)->with('totalamount',$totalamount)->with('totaltrips',$totaltrips);
    }

    public function detail(Request $request, $id)
    {
        $currency = Currency::where('statut', 'yes')->first();
        $pendingcollections =DB::table('tj_requete')
        ->join('tj_conducteur','tj_requete.id_conducteur','=','tj_conducteur.id')
        ->join('bookingtypes','tj_requete.booking_type_id','=','bookingtypes.id')
        ->join('tj_vehicule','tj_requete.vehicle_id','=','tj_vehicule.id')
        ->join('car_model','tj_vehicule.model','=','car_model.id')
        ->join('brands','tj_vehicule.brand','=','brands.id')
        ->select(DB::raw("tj_requete.id,tj_requete.ride_required_on_date,tj_requete.ride_required_on_time,tj_vehicule.primary_image_id, tj_requete.id_conducteur, concat(tj_conducteur.nom,' ',tj_conducteur.prenom) as drivername,tj_vehicule.car_make,tj_vehicule.numberplate,
brands.name as brandname,car_model.name as modelname,tj_requete.montant,bookingtypes.bookingtype"))
        ->where('tj_requete.id_payment_method', '=', '5')
        ->where('tj_requete.statut', '=', 'completed')
        ->where('tj_requete.id_conducteur', '=', $id)
        ->whereNull('tj_requete.cod_collected_transaction_id')
        ->orderByDesc('tj_requete.montant')
        ->get();

        foreach($pendingcollections as $row){
            $row->ride_required_on_date= date("d-m-y", strtotime($row->ride_required_on_date))." ".date("h:m A", strtotime($row->ride_required_on_time));
        }
        //echo json_encode($pendingcollections,JSON_PRETTY_PRINT);
        $totalamount = $pendingcollections->sum('montant');
        $totaltrips = $pendingcollections->count('id');
        $drivername = $pendingcollections->first()->drivername;
        return view('cash_collection.detail')->with('currency',$currency)->with('pendingcollections',$pendingcollections)->with('totalamount',$totalamount)->with('totaltrips',$totaltrips)->with('drivername',$drivername)->with('driverid',$id);
    }

    public function collect(Request $request)
    {
        $driverid= $request->input('driverid');
        $transactionid = (string) Str::uuid();
        $adminid = Auth::id();
        DB::table('ride_cash_collections')->insert([
            'driver_id' => $driverid,
            'total_rides' => $request->input('total_rides'),
            'tatal_amount' => $request->input('tatal_amount'),
            'transaction_id' => $transactionid,
            'admin_id' => $adminid,
            'created' => now()
            
        ]);

        DB::table('tj_requete')
        ->where('tj_requete.id_payment_method', '=', '5')
        ->where('tj_requete.statut', '=', 'completed')
        ->where('tj_requete.id_conducteur', '=', $driverid)
        ->whereNull('tj_requete.cod_collected_transaction_id')
        ->update(['cod_collected_transaction_id' => $transactionid, 'updated_at' => now()]);
        return redirect('cash_collection');
    }

    public function collected(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        $cashcollections =DB::table('tj_requete')->join('tj_conducteur','tj_requete.id_conducteur','=','tj_conducteur.id')
        ->select(DB::raw("tj_requete.id_conducteur as id,tj_requete.updated_at,tj_requete.cod_collected_transaction_id, concat(tj_conducteur.nom,' ',tj_conducteur.prenom) as drivername,tj_conducteur.photo_path, count(tj_requete.id) as rides,sum(tj_requete.montant) as pendingamount"))
        ->where('tj_requete.id_payment_method', '=', '5')
        ->where('tj_requete.statut', '=', 'completed')
        ->whereNotNull('tj_requete.cod_collected_transaction_id')
        ->groupBy('tj_requete.id_conducteur')
        ->orderByDesc(DB::raw('sum(tj_requete.montant)'))
        ->get();
        $totalamount = $cashcollections->sum('pendingamount');
        $totaltrips = $cashcollections->sum('rides');
        
        foreach($cashcollections as $row){
            $row->updated_at= date("d-m-y", strtotime($row->updated_at))." ".date("h:m A", strtotime($row->updated_at));
        }
        //echo json_encode($cashcollections,JSON_PRETTY_PRINT);
        return view('cash_collection.collected')->with('currency',$currency)->with('cashcollections',$cashcollections)->with('totalamount',$totalamount)
        ->with('totaltrips',$totaltrips);
    }

    public function coldetail(Request $request, $id,$transactionid)
    {
        $currency = Currency::where('statut', 'yes')->first();
        $pendingcollections =DB::table('tj_requete')
        ->join('tj_conducteur','tj_requete.id_conducteur','=','tj_conducteur.id')
        ->join('bookingtypes','tj_requete.booking_type_id','=','bookingtypes.id')
        ->join('tj_vehicule','tj_requete.vehicle_id','=','tj_vehicule.id')
        ->join('car_model','tj_vehicule.model','=','car_model.id')
        ->join('brands','tj_vehicule.brand','=','brands.id')
        ->select(DB::raw("tj_requete.id,tj_requete.ride_required_on_date,tj_requete.ride_required_on_time,tj_requete.updated_at,tj_vehicule.primary_image_id, tj_requete.id_conducteur, concat(tj_conducteur.nom,' ',tj_conducteur.prenom) as drivername,tj_vehicule.car_make,tj_vehicule.numberplate,
brands.name as brandname,car_model.name as modelname,tj_requete.montant,bookingtypes.bookingtype"))
        ->where('tj_requete.id_payment_method', '=', '5')
        ->where('tj_requete.statut', '=', 'completed')
        ->where('tj_requete.id_conducteur', '=', $id)
        ->whereNotNull('tj_requete.cod_collected_transaction_id')
        ->where('tj_requete.cod_collected_transaction_id','=',$transactionid)
        ->orderByDesc('tj_requete.montant')
        ->get();

        foreach($pendingcollections as $row){
            $row->ride_required_on_date= date("d-m-y", strtotime($row->ride_required_on_date))." ".date("h:m A", strtotime($row->ride_required_on_time));
            $row->updated_at= date("d-m-y", strtotime($row->updated_at))." ".date("h:m A", strtotime($row->updated_at));
        }
        //echo json_encode($pendingcollections,JSON_PRETTY_PRINT);
        $totalamount = $pendingcollections->sum('montant');
        $totaltrips = $pendingcollections->count('id');
        $drivername = $pendingcollections->first()->drivername;
        $transactionDate = date("d-m-y", strtotime($pendingcollections->first()->updated_at))." ".date("h:m A", strtotime($pendingcollections->first()->updated_at));
        return view('cash_collection.coldetail')->with('currency',$currency)->with('pendingcollections',$pendingcollections)
        ->with('totalamount',$totalamount)->with('totaltrips',$totaltrips)->with('drivername',$drivername)
        ->with('driverid',$id)->with('transactionDate',$transactionDate);
    }
}
