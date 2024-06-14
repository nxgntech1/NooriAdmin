<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'libelle') {
            $search = $request->input('search');
            $tax = DB::table('tj_tax')
                ->where('tj_tax.libelle', 'LIKE', '%' . $search . '%')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'country') {
            $search = $request->input('search');
            $tax = DB::table('tj_tax')
                ->where('tj_tax.country', 'LIKE', '%' . $search . '%')
                ->paginate(20);
        } else {
            $tax = DB::table('tj_tax')
                ->paginate(10);
        }
        return view("administration_tools.tax.index")->with("taxes", $tax);
    }

    public function create()
    {
        return view("administration_tools.tax.create");
    }
    public function store(Request $request){

        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',
            'tax' => 'required',
            'type' => 'required',
            'country' => 'required',
        ], $messages = [
                'libelle.required' => 'The tax label is required!',
                'tax.required' => 'The tax field is required!',
                'type.required' => 'The tax type is required!',
                'country.required' => 'The country  is required!',
            ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $data = $request->all();
        $date = date('Y-m-d H:i:s');
        Tax::create([
            'libelle'=>$data['libelle'],
            'value'=>$data['tax'],
            'type'=>$data['type'],
            'country'=>$data['country'],
            'statut'=>($request->has('statut')) ? 'yes' :'no',
            'creer'=>$date
        ]);
        return redirect('administration_tools/tax');

    }
    public function edit($id)
    {

        $Tax = Tax::find($id);
        return view("administration_tools.tax.edit")->with('Tax', $Tax);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',
            'tax' => 'required',
            'type' => 'required',
            'country' => 'required',
        ], $messages = [
                'libelle.required' => 'The tax label is required!',
                'tax.required' => 'The tax field is required!',
                'type.required' => 'The tax type is required!',
                'country.required' => 'The country  is required!',
            ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $name = $request->input('libelle');
        $value = $request->input('tax');
        $type = $request->input('type');
        $enabled = $request->has('statut') ? 'yes' : 'no';
        $country = $request->input('country');
        $modifier = date('Y-m-d H:i:s');
        $Tax = Tax::find($id);

        if ($Tax) {
            $Tax->libelle = $name;
            $Tax->value = $value;
            $Tax->type = $type;
            $Tax->statut = $enabled;
            $Tax->country = $country;
            $Tax->modifier = $modifier;
            $Tax->save();
        }
        return redirect('administration_tools/tax');

    }
    public function delete($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Tax::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Tax::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }


    public function show($id)
    {

        $Tax = Tax::find($id);
        return view("administration_tools.tax.show")->with('Tax', $Tax);

    }

    public function changeStatus(Request $request, $id)
    {
        $Tax = Tax::find($id);
        if ($Tax->statut == 'no') {
            $Tax->statut = 'yes';
            $comm = DB::table('tj_tax')->where('id', '!=', $id)->update(['statut' => 'no']);
        } else {
            $Tax->statut = 'no';
            $comm = DB::table('tj_tax')->where('id', '!=', $id)->update(['statut' => 'yes']);
        }


        $Tax->save();
        return redirect()->back();


    }

    public function searchTax(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'Name') {
            $search = $request->input('search');
            $Tax = DB::table('tj_tax')
                ->select('tj_tax.*')
                ->where('tj_tax.libelle', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'Type') {
            $search = $request->input('search');
            $Tax = DB::table('tj_tax')
                ->select('tj_tax.*')
                ->where('tj_tax.type', 'LIKE', '%' . $search . '%')
                ->paginate(10);
        } else {
            $Tax = DB::table('tj_tax')
                ->select('tj_tax.*')
                ->paginate(10);
        }
        return view('administration_tools.tax.index')->with("Tax", $Tax);
    }
    public function toggalSwitch(Request $request)
    {
        $ischeck = $request->input('ischeck');
        $id = $request->input('id');
        $tax = Tax::find($id);

        if ($ischeck == "true") {
            $tax->statut = 'yes';
        } else {
            $tax->statut = 'no';
        }
        $tax->save();

    }
}