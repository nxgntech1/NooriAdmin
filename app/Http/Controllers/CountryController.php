<?php

namespace App\Http\Controllers;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

	  public function index(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search=='libelle') {
            $search = $request->input('search');
            $countries = DB::table('tj_country')
            ->where('tj_country.libelle','LIKE','%'.$search.'%')
            ->paginate(20);

        }else if ($request->has('search') && $request->search != '' && $request->selected_search=='code') {
            $search = $request->input('search');
            $countries = DB::table('tj_country')
            ->where('tj_country.code','LIKE','%'.$search.'%')
            ->paginate(20);

        }else{

        $countries = DB::table('tj_country')
        ->paginate(20);

        }
       return view("administration_tools.country.index")->with("countries",$countries);
    }

    public function editCountry($id){
        $country = DB::table('tj_country')
        ->where('id',$id)
        ->first();

        return view('administration_tools.country.edit')->with('country',$country);

    }

    public function show($id)
    {
        $countries = DB::table('tj_country')
        ->where('id',$id)
        ->first();
        return view('administration_tools.country.show')->with('countries',$countries);

    }

    public function create(Request $request)
    {
        return view('administration_tools.country.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all() ,$rules = [
            'libelle' => 'required',
            'code' => 'required',
        ],  $messages = [
          'libelle.required' => 'The Country Name field is required!',
          'code.required' => 'The Country Code field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with(['message' => $messages])->withInput();
        }

        $name = $request->input('libelle');
        $code = $request->input('code');
        $status = $request->input('statut');
        if($status == "on"){
        $status="yes";
        }else{
            $status = "no";
        }
       $country = new Country;

      if($country){
        $country->libelle = $name;
        $country->code = $code;
        $country->statut = $status;
        $country->creer = date('Y-m-d H:i:s');
        $country->created_at = date('Y-m-d H:i:s');
        $country->modifier = date('Y-m-d H:i:s');

        $country->save();
      }

      return redirect('administration_tools/country');
    }

    public function update($id,Request $request)
    {
       $name = $request->input('libelle');
       $code = $request->input('code');
       $status = $request->input('statut');
       if($status == "on"){
        $status="yes";
        }else
        {
            $status = "no";
        }

       $country =Country::find($id);

      if($country){
        $country->libelle = $name;
        $country->code = $code;
        $country->statut = $status;
        $country->modifier = date('Y-m-d H:i:s');

        $country->save();
      }

      return redirect('administration_tools/country');
    }

    public function changeStatus($id)
    {
        $country=Country::find($id);
        if($country->statut == 'no') {
            $country->statut = 'yes';
        }
        else{
          $country->statut = 'no';
        }

        $country->save();
        return redirect()->back();

    }

    public function toggalSwitch(Request $request){

      $ischeck=$request->input('ischeck');
      $id=$request->input('id');
      $country = Country::find($id);

      if($ischeck=="true"){
        $country->statut = 'yes';
      }else{
        $country->statut = 'no';
      }
      
      $country->save();

  }
}
