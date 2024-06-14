<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleType;
use App\Models\Currency;
use Validator;

class CarModelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'name') {
            $search = $request->input('search');
            $carModel = DB::table('car_model')
            ->select('car_model.*', DB::raw('COUNT(tj_vehicule.model) as vehicle_count'))
            ->leftJoin('tj_vehicule', 'car_model.id', '=', 'tj_vehicule.model')
            ->where('car_model.name', 'LIKE', '%' . $search . '%')
            ->whereNull('car_model.deleted_at')
            ->groupBy('car_model.id')
            ->paginate(10);
            
            
            // DB::table('car_model')
            // ->select('car_model.*',DB::raw('COUNT(tj_vehicule.model) as vehicle_count'))
            // ->leftJoin('tj_vehicule','car_model.id','=','tj_vehicule.model')
            //     ->where('car_model.name', 'LIKE', '%' . $search . '%')
            //     ->where('car_model.deleted_at', '=', NULL)
            //     ->groupBy('car_model.id')
            //     ->get()
            //     ->paginate(10);
               
        }  else {
            //$carModel = CarModel::paginate(10);
            $carModel = DB::table('car_model')
            ->select('car_model.*', DB::raw('COUNT(tj_vehicule.model) as vehicle_count'))
            ->leftJoin('tj_vehicule', 'car_model.id', '=', 'tj_vehicule.model')
            ->whereNull('car_model.deleted_at')
            ->groupBy('car_model.id')
            ->paginate(10);
        }
        $brand=DB::table('brands')->select('*')->get();
        $vehicleType = VehicleType::all();
        return view("carModel.index")->with("carModel", $carModel)->with("brand",$brand)->with('vehicleType',$vehicleType)->with('currency',$currency);
    }

    public function create()
    {
        $brand=DB::table('brands')->select('*')->get();
        $vehicleType = VehicleType::all();
        return view("carModel.create")->with('brand',$brand)->with('vehicleType',$vehicleType);
    }

    public function storecarmodel(Request $request)
    {
        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
        }
        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required',
            'brand' => 'required',
            'vehicle_id'=> 'required',
            'image' => $image_validation,
            

        ], $messages = [
            'name.required' => 'The  Name field is required!',
            'brand.required' => 'The brand field is required!',
            'vehicle_id.required' =>'The vehicle Type field is required!',
            'image.required' => 'The Image field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('car_model/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $carModel = new CarModel;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'image_carmodel_' . $time;
            
            $file->move(public_path('assets/images/vehicle_models'), $filename);
            $carModel->imageid = $filename;
        }

        $carModel->name = $request->input('name');
        $carModel->brand_id = $request->input('brand');
        $carModel->vehicle_type_id = $request->input('vehicle_id');
        $carModel->status = $request->input('status') ? 'yes' : 'no';
        $carModel->allow_cod = $request->input('allowcod') ? 'yes' : 'no';
        $carModel->created_at = date('Y-m-d H:i:s');
        $carModel->modifier = date('Y-m-d H:i:s');
        $carModel->updated_at = date('Y-m-d H:i:s');

        $carModel->save();

        return redirect('car_model');

    }


    public function edit($id)
    {
        $carModel = DB::table('car_model')->where('id', "=", $id)->first();
        $brand=DB::table('brands')->select('*')->get();
        $vehicleType = VehicleType::all();
        //echo json_encode($carModel,JSON_PRETTY_PRINT);
        return view("carModel.edit")->with("carModel", $carModel)->with("brand", $brand)->with('vehicleType', $vehicleType);
    }

    public function UpdateCarModel(Request $request, $id)
    {
        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
        }
        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required',
            'brand_name' => 'required',
            'vehicle_id'=> 'required',
            'image' => $image_validation,

        ], $messages = [
            'name.required' => 'The  Name field is required!',
            'brand_name.required' => 'The brand field is required!',
            'vehicle_id.required' =>'The vehicle Type field is required!',
            'image.required' => 'The Image field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('car_model/edit')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $name = $request->input('name');
        $brand = $request->input('brand_name');
        $status = $request->input('status') ? 'yes' : 'no';
        $allowcod = $request->input('allowcod') ? 'yes' : 'no';
        $vehicle_type = $request->input('vehicle_id');

        $carModel = CarModel::find($id);
        if ($carModel) {
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'image_carmodel_' . $time;
                
                $file->move(public_path('assets/images/vehicle_models'), $filename);
                $carModel->imageid = $filename;
            }

            $carModel->name = $name;
            $carModel->brand_id = $brand;
            $carModel->status = $status;
            $carModel->allow_cod =$allowcod;
            $carModel->vehicle_type_id = $vehicle_type;
            $carModel->updated_at = date('Y-m-d H:i:s');

            $carModel->save();
        }

        return redirect('car_model');
    }

    public function deleteCarModel($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $carModel = CarModel::find($id[$i]);
                    $carModel->delete();
                }

            } else {
                $carModel = CarModel::find($id);
                $carModel->delete();
            }

        }

        return redirect()->back();
    }

    public function changeStatus($id)
    {
        $carModel = CarModel::find($id);
        if ($carModel->status == 'no') {
            $carModel->status = 'yes';
        } else {
            $carModel->status = 'no';
        }

        $carModel->save();
        return redirect()->back();

    }

    public function toggalSwitch(Request $request){
            $ischeck=$request->input('ischeck');
            $id=$request->input('id');
            $isactive=$request->input('isactive');
            $carModel = CarModel::find($id);
            if($isactive=="true")
            {
                if($ischeck=="true"){
                    $carModel->status = 'yes';
                }else{
                    $carModel->status = 'no';
                }
            }
            else{
                if($ischeck=="true"){
                    $carModel->allow_cod = 'yes';
                }else{
                    $carModel->allow_cod = 'no';
                }
            }
              $carModel->save();

    }


}
