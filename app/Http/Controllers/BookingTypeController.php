<?php

namespace App\Http\Controllers;

use App\Models\bookingtypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleType;
use Validator;

class BookingTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'name') {
            $search = $request->input('search');
            $bookingtypeModel = DB::table('bookingtypes')
                ->where('bookingtypes.bookingtype', 'LIKE', '%' . $search . '%')
                ->where('bookingtypes.status', '=', "yes")
                ->paginate(10);
                //echo 'if';
        }  else {
            $bookingtypeModel = bookingtypes::paginate(10);
            //var_dump($bookingtypeModel);
           //echo json_encode($bookingtypeModel, JSON_PRETTY_PRINT);
        }
        
        
        return view("bookingtypes.index")->with("bookingtypes", $bookingtypeModel);
    }

    public function create()
    {
        return view("bookingtypes.create");
    }

    public function storebookingtypemodel(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required'

        ], $messages = [
            'name.required' => 'The Booking Type field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('bookingtypes/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $bookingtypes = new bookingtypes;
        $bookingtypes->bookingtype = $request->input('name');
        
        $bookingtypes->status = $request->input('status') ? 'yes' : 'no';
        
        $bookingtypes->save();

        return redirect('bookingtypes');

    }

    public function edit($id)
    {
        $bookingtypes = bookingtypes::where('id', "=", $id)->first();
        return view("bookingtypes.edit")->with("bookingtypes", $bookingtypes);
    }

    public function UpdateBookingType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required',

        ], $messages = [
            'name.required' => 'The Booking Type field is required!',
            
            
        ]);

        if ($validator->fails()) {
            return redirect('bookingtypes/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $name = $request->input('name');
        $status = $request->input('status') ? 'yes' : 'no';

        $bookingtypemodel = bookingtypes::where('id', $id)->first();
        if ($bookingtypemodel) {
           
            DB::table('bookingtypes')
            ->where('id', $id)
            ->update([
                'bookingtype' => $name,
                'status' => $status,
            ]);
        }

        return redirect('bookingtypes');
    }

    public function deleteBookingType($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    // $bookingtypeModel = bookingtypes::find($id[$i]);
                    // $bookingtypeModel->delete();
                    $bookingtypeModel = bookingtypes::where('id', $id[$i])->first();
                    if($bookingtypeModel)
                    {
                        DB::table('bookingtypes')
                        ->where('id', $id)
                        ->delete();
                    }
                }

            } else {
                $bookingtypeModel = bookingtypes::where('id', $id)->first();
                if($bookingtypeModel)
                {
                    DB::table('bookingtypes')
                    ->where('id', $id)
                    ->delete();
                }
                
            }

        }

        return redirect()->back();
    }

    public function toggalSwitch(Request $request){
        $ischeck=$request->input('ischeck');
        $id=$request->input('id');
        
        $bookingtypeModel = bookingtypes::where('id', $id)->first();
        if($bookingtypeModel)
        {
            
            DB::table('bookingtypes')
            ->where('id', $id)
            ->update([
                'status' => $ischeck=="true" ? 'yes' : 'no',
            ]);
            
        }

}

}