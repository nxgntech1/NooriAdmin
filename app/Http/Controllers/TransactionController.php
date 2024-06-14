<?php

namespace App\Http\Controllers;
use App\Models\Currency;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index(Request $request,$id='')
    {
        if($id){
            if ($request->has('search') && $request->search != '' && $request->selected_search=='transaction_id') {

                $search = $request->input('search');

                $transaction = DB::table('tj_transaction')
                ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
                ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
                ->addSelect('tj_transaction.*','tj_payment_method.image')
                ->where('tj_transaction.id','LIKE','%'.$search.'%')
                ->where('tj_transaction.id_user_app','=',$id)
                ->orderBy('tj_transaction.id','desc')
                ->paginate(20);
              }elseif($request->has('payment_status') && $request->payment_status != '' && $request->selected_search=='payment_status'){
                $search = $request->input('payment_status');

                $transaction = DB::table('tj_transaction')
                ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
                ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
                ->addSelect('tj_transaction.*','tj_payment_method.image')
                ->where('tj_transaction.payment_status','LIKE','%'.$search.'%')
                ->where('tj_transaction.id_user_app','=',$id)
                ->orderBy('tj_transaction.id', 'desc')
                ->paginate(20);
              }
              else{




              $transaction = DB::table('tj_transaction')
              ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
              ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
              ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
              ->addSelect('tj_transaction.*','tj_payment_method.image')
              ->where('tj_transaction.id_user_app','=',$id)
              ->orderBy('tj_transaction.id', 'desc')
              ->paginate(20);

              }
        }
        else{
            if ($request->has('search') && $request->search != '' && $request->selected_search=='transaction_id') {

                $search = $request->input('search');

                $transaction = DB::table('tj_transaction')
                ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
                ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
                ->addSelect('tj_transaction.*','tj_payment_method.image')
                ->where('tj_transaction.id','LIKE','%'.$search.'%')
                ->orderBy('tj_transaction.id', 'desc')
                ->paginate(20);
              }elseif($request->has('payment_status') && $request->payment_status != '' && $request->selected_search=='payment_status'){
                $search = $request->input('payment_status');

                $transaction = DB::table('tj_transaction')
                ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
                ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
                ->addSelect('tj_transaction.*','tj_payment_method.image')
                ->where('tj_transaction.payment_status','LIKE','%'.$search.'%')
                ->orderBy('tj_transaction.id', 'desc')
                ->paginate(20);
              }
              else{

              $transaction = DB::table('tj_transaction')
              ->join('tj_user_app', 'tj_transaction.id_user_app', '=', 'tj_user_app.id')
              ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_transaction.payment_method')
              ->select('tj_user_app.id as userId','tj_user_app.nom as lastname','tj_user_app.prenom as firstname')
              ->addSelect('tj_transaction.*','tj_payment_method.image')->orderBy('tj_transaction.id', 'desc')
              ->paginate(20);

              }
        }
        $currency = Currency::where('statut', 'yes')->first();
        return view("transactions.index")->with('id',$id)->with('transaction',$transaction)->with('currency',$currency);
    }
    public function driverWallet(Request $request,$id=''){

      if ($request->has('search') && $request->search != '' && $request->selected_search=='transaction_id') {

          $search = $request->input('search');

      $transaction = DB::table('tj_conducteur_transaction')
        ->join('tj_conducteur', 'tj_conducteur_transaction.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_conducteur_transaction.payment_method')
        ->select('tj_conducteur.id as userId', 'tj_conducteur.nom as lastname', 'tj_conducteur.prenom as firstname')
        ->addSelect('tj_conducteur_transaction.*','tj_payment_method.image')
        ->where('tj_conducteur_transaction.id', 'LIKE', '%' . $search . '%')
        ->orderBy('tj_conducteur_transaction.id', 'desc');
        }else{
          $transaction = DB::table('tj_conducteur_transaction')
          ->join('tj_conducteur', 'tj_conducteur_transaction.id_conducteur', '=', 'tj_conducteur.id')
          ->join('tj_payment_method', 'tj_payment_method.libelle', '=', 'tj_conducteur_transaction.payment_method')
          ->select('tj_conducteur.id as userId','tj_conducteur.nom as lastname','tj_conducteur.prenom as firstname')
          ->addSelect('tj_conducteur_transaction.*','tj_payment_method.image')->orderBy('tj_conducteur_transaction.id', 'desc');
        }
        if($id){
          $transaction->where('tj_conducteur_transaction.id_conducteur',$id);
        }
      $transaction = $transaction->paginate(20);


      $currency = Currency::where('statut', 'yes')->first();
      return view("transactions.driver_wallet")->with('transaction',$transaction)->with('currency',$currency)->with('id',$id);
    }
}
