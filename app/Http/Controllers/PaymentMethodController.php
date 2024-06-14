<?php

namespace App\Http\Controllers;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{ 

    public function __construct()
    {
        $this->middleware('auth');
    }
    
	    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search=='libelle') {
            $search = $request->input('search');
            $payments = DB::table('tj_payment_method')
            ->where('tj_payment_method.libelle','LIKE','%'.$search.'%')
            ->paginate(20);
            
        }else{

        $payments = DB::table('tj_payment_method')
        ->paginate(20);

        }

       return view("administration_tools.payment_method.index")->with("payments",$payments);
    }


  public function show($id)
    {
        $payment_method = DB::table('tj_payment_method')
        ->where('id',$id)->first();
  
    	return view('administration_tools.payment_method.show')->with('payment_method',$payment_method);
    }
    public function changeStatus($id)
    {
        $payment_method = PaymentMethod::find($id);

        if($payment_method->statut == 'no')
        {
            $payment_method->statut = 'yes';
        }
        else{
            $payment_method->statut = 'no';
        }
        $payment_method->save();
    	return redirect()->back();
    }
}