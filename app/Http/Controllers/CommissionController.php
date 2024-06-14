<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $commission = DB::table('tj_commission')->first();
        return view("administration_tools.commission.index")->with("commission", $commission);
    }

    public function edit($id)
    {

        $commission = Commission::find($id);
        return view("administration_tools.commission.edit")->with('commission', $commission);

    }

    public function update(Request $request, $id)
    {

        $status = $request->has('statut') ? 'yes' : 'no';
        $value = $request->input('value');
        $type = $request->input('type');
        $modifier = $request->modifier = date('Y-m-d H:i:s');

        $commission = Commission::find($id);
        if ($commission) {
            $commission->statut = $status;
            $commission->value = $value;
            $commission->type = $type;
            $commission->modifier = $modifier;

            $commission->save();
            return redirect()->back();
        }
    }

    public function show($id)
    {

        $commission = Commission::find($id);
        return view("administration_tools.commission.show")->with('commission', $commission);

    }

    public function changeStatus(Request $request, $id)
    {
        $commission = Commission::find($id);
        if ($commission->statut == 'no') {
            $commission->statut = 'yes';
            $comm=DB::table('tj_commission')->where('id','!=',$id)->update(['statut'=>'no']);
        }  else {
            $commission->statut = 'no';
            $comm=DB::table('tj_commission')->where('id','!=',$id)->update(['statut'=>'yes']);
        }


        $commission->save();
        return redirect()->back();


    }

    public function searchCommision(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'Name') {
            $search = $request->input('search');
            $commmision=DB::table('tj_commission')
                ->select('tj_commission.*')
                ->where('tj_commission.libelle','LIKE','%' . $search . '%')
                ->paginate(10);

        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'Type') {
            $search = $request->input('search');
            $commmision=DB::table('tj_commission')
                ->select('tj_commission.*')
                ->where('tj_commission.type','LIKE','%' . $search . '%')
                ->paginate(10);
        } else {
            $commmision=DB::table('tj_commission')
                ->select('tj_commission.*')
                ->paginate(10);
        }
        return view('administration_tools.commission.index')->with("commissions",$commmision);
    }
    public function toggalSwitch(Request $request){
            $ischeck=$request->input('ischeck');
            $id=$request->input('id');
            $commission = Commission::find($id);

            if($ischeck=="true"){
              $commission->statut = 'yes';
            }else{
              $commission->statut = 'no';
            }
              $commission->save();

    }
}
