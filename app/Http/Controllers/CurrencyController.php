<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'libelle') {
            $search = $request->input('search');
            $currencies = DB::table('tj_currency')
                ->where('tj_currency.libelle', 'LIKE', '%' . $search . '%')
                ->paginate(20);

        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'symbole') {
            $search = $request->input('search');
            $currencies = DB::table('tj_currency')
                ->where('tj_currency.symbole', 'LIKE', '%' . $search . '%')
                ->paginate(20);

        } else {

            $currencies = DB::table('tj_currency')
                ->paginate(20);

        }
        return view("administration_tools.currency.index")->with("currencies", $currencies);
    }

    public function createCurrency()
    {
    	return view("administration_tools.currency.create");
    }

    public function currencyEdit(Request $request, $id)
    {
		$currency = DB::table('tj_currency')
            ->where('id', $id)->first();
        return view("administration_tools.currency.edit", compact('currency'));
    }

    public function show($id)
    {
        $currencies = DB::table('tj_currency')
            ->where('id', $id)->first();

        return view('administration_tools.currency.show')->with('currencies', $currencies);
    }

    public function update($id, Request $request)
    {
        $name = $request->input('libelle');
        $symbol = $request->input('symbol');
        $status = $request->input('statut');
        $decimal = $request->input('decimal_digit');
        $symbol_at_right = $request->has('symbol_at_right')?"true":"false";

        if ($status == "on") {

            DB::table('tj_currency')
                ->where('statut', "yes")
                ->update(array('statut' => "no"));

            $status = "yes";
        } else {
            $status = "no";
        }

        $currencies = Currency::find($id);

        if ($currencies) {
            $currencies->libelle = $name;
            $currencies->symbole = $symbol;
            $currencies->statut = $status;
            $currencies->symbol_at_right = $symbol_at_right;
            $currencies->decimal_digit = $decimal;
            $currencies->modifier = date('Y-m-d H:i:s');

            $currencies->save();
        }


        return redirect('administration_tools/currency');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',
            'symbol' => 'required',


        ], $messages = [
            'libelle.required' => 'The Name field is required!',
            'symbol.required' => 'The Symbol field is required!',


        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }


        $name = $request->input('libelle');
        $symbol = $request->input('symbol');
        $status = $request->input('statut');
        $decimal = $request->input('decimal_digit');
        $symbol_at_right = $request->has('symbol_at_right') ? "true" : "false";

        if ($status == "yes") {

            DB::table('tj_currency')
                ->where('statut', "yes")
                ->update(array('statut' => "no"));

            $status = "yes";
        }
        else{
          $status='no';
        }

        $currencies = new Currency;

        if ($currencies) {
            $currencies->libelle = $name;
            $currencies->symbole = $symbol;
            $currencies->statut = $status;
            $currencies->decimal_digit = $decimal;
            $currencies->symbol_at_right = $symbol_at_right;
            $currencies->modifier = date('Y-m-d H:i:s');

            $currencies->save();
        }


        return redirect('administration_tools/currency');
    }
    public function delete($id){
      if ($id != "") {

           $id = json_decode($id);

           if (is_array($id)) {

               for ($i = 0; $i < count($id); $i++) {
                   $user = Currency::find($id[$i]);
                   $user->delete();
               }

           } else {
               $user = Currency::find($id);
               $user->delete();
           }

       }

       return redirect()->back();
    }

    public function changeStatus($id)
    {
        $currencies = Currency::find($id);
        if ($currencies->statut == 'no') {

            DB::table('tj_currency')
                ->where('statut', "yes")
                ->update(array('statut' => "no"));

            $currencies->statut = 'yes';
        } else {
            $currencies->statut = 'no';
        }

        $currencies->save();
        return redirect()->back();

    }

    public function toggalSwitch(Request $request)
    {
        $ischeck = $request->input('ischeck');
        $id = $request->input('id');
        $currencies = Currency::find($id);
		
		$response = array();
        if($currencies->statut == 'yes')
        {
            
            $messages = 'You can not disable all currencies';
            $response['error'] = $messages;
    
        }else{
	        if ($ischeck == "true") {
	
	            DB::table('tj_currency')
	                ->where('statut', "yes")
	                ->update(array('statut' => "no"));
	
	            $currencies->statut = 'yes';
	        } else {
	            $currencies->statut = 'no';
	        }
        	$currencies->save();
    	}
    return response()->json($response);
    }

}
