<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pricing_by_car_models;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\bookingtypes;
use App\Models\VehicleType;
use App\Models\Currency;
use Validator;

class AddOnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'brand') {
            $search = $request->input('search');
            $carModelPricing = DB::table('pricing_by_car_models')
                ->join('car_model', 'pricing_by_car_models.CarModelID', '=', 'car_model.id')
                ->join('bookingtypes', 'pricing_by_car_models.BookingTypeID', '=', 'bookingtypes.id')
                ->join('brands','car_model.brand_id','=','brands.id')
                ->join('tj_type_vehicule','car_model.vehicle_type_id','=','tj_type_vehicule.id')
                ->select('pricing_by_car_models.*','car_model.name as modelname','bookingtypes.bookingtype as BookingType','car_model.brand_id as brandid','brands.name as BrandName','car_model.vehicle_type_id as vehicleid','tj_type_vehicule.libelle as vehicletype')
                ->where('brands.name', 'LIKE', '%' . $search . '%')
                ->where('car_model.status', '=', 'yes')
                ->where('pricing_by_car_models.is_Add_on','=','yes')
                ->paginate(10);
        } 
        else if ($request->has('search') && $request->search != '' && $request->selected_search == 'model') {
            $search = $request->input('search');
            $carModelPricing = DB::table('pricing_by_car_models')
                ->join('car_model', 'pricing_by_car_models.CarModelID', '=', 'car_model.id')
                ->join('bookingtypes', 'pricing_by_car_models.BookingTypeID', '=', 'bookingtypes.id')
                ->join('brands','car_model.brand_id','=','brands.id')
                ->join('tj_type_vehicule','car_model.vehicle_type_id','=','tj_type_vehicule.id')
                ->select('pricing_by_car_models.*','car_model.name as modelname','bookingtypes.bookingtype as BookingType','car_model.brand_id as brandid','brands.name as BrandName','car_model.vehicle_type_id as vehicleid','tj_type_vehicule.libelle as vehicletype')
                ->where('car_model.name', 'LIKE', '%' . $search . '%')
                ->where('car_model.status', '=', 'yes')
                ->where('pricing_by_car_models.is_Add_on','=','yes')
                ->paginate(10);
        }
        else if ($request->has('search') && $request->search != '' && $request->selected_search == 'bookingtype') {
            $search = $request->input('search');
            $carModelPricing = DB::table('pricing_by_car_models')
                ->join('car_model', 'pricing_by_car_models.CarModelID', '=', 'car_model.id')
                ->join('bookingtypes', 'pricing_by_car_models.BookingTypeID', '=', 'bookingtypes.id')
                ->join('brands','car_model.brand_id','=','brands.id')
                ->join('tj_type_vehicule','car_model.vehicle_type_id','=','tj_type_vehicule.id')
                ->select('pricing_by_car_models.*','car_model.name as modelname','bookingtypes.bookingtype as BookingType','car_model.brand_id as brandid','brands.name as BrandName','car_model.vehicle_type_id as vehicleid','tj_type_vehicule.libelle as vehicletype')
                ->where('bookingtypes.BookingType', 'LIKE', '%' . $search . '%')
                ->where('car_model.status', '=', 'yes')
                ->where('pricing_by_car_models.is_Add_on','=','yes')
                ->paginate(10);
        }
        else {
            $carModelPricing = DB::table('pricing_by_car_models')
                ->join('car_model', 'pricing_by_car_models.CarModelID', '=', 'car_model.id')
                ->join('bookingtypes', 'pricing_by_car_models.BookingTypeID', '=', 'bookingtypes.id')
                ->join('brands','car_model.brand_id','=','brands.id')
                ->join('tj_type_vehicule','car_model.vehicle_type_id','=','tj_type_vehicule.id')
                ->select('pricing_by_car_models.*','car_model.name as modelname','bookingtypes.bookingtype as BookingType','car_model.brand_id as brandid','brands.name as BrandName','car_model.vehicle_type_id as vehicleid','tj_type_vehicule.libelle as vehicletype')
                ->where('car_model.status', '=', 'yes')
                ->where('pricing_by_car_models.is_Add_on','=','yes')
                ->paginate(10);
        }
       // echo json_encode($carModelPricing, JSON_PRETTY_PRINT);
        return view("addon.index")->with("carModelPricing", $carModelPricing)->with('currency',$currency);
    }

    
    public function create()
    {
        $vehicletype = VehicleType::all();
        $brand = Brand::all();
        $carmodel = CarModel::all();
        $bookingtype = bookingtypes::all();
        return view("addon.create")->with('vehicletype',$vehicletype)->with('brand',$brand)->with('carmodel',$carmodel)->with('bookingtype',$bookingtype);
    }

    public function storecarmodelprice(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'vehicle_id' => 'required',
            'brand' => 'required',
            'carmodel_id' => 'required',
            'hours' => 'required',
            'kms' => 'required',
            'price' => 'required'

        ], $messages = [
            'vehicle_id.required' => 'The Vehicle Type field is required!',
            'brand.required' => 'The Vehicle Brand field is required!',
            'carmodel_id.required' => 'The Car Model field is required!',
            'hours.required' => 'The Hours field is required!',
            'kms.required' => 'The Kilometers field is required!',
            'price.required' => 'The Price field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('addon/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $carmodelprice = new pricing_by_car_models();
        $carmodelprice->CarModelID = $request->input('carmodel_id');
        $carmodelprice->BookingTypeID= 3;
        $carmodelprice->Price = $request->input('price');
        $carmodelprice->Status = $request->input('status') ? 'yes' : 'no';
        $carmodelprice->is_Add_on = 'yes';
        $carmodelprice->hours= $request->input('hours');
        $carmodelprice->kms= $request->input('kms');

        $carmodelprice->save();

        return redirect('addon');

    }

    public function edit($id)
    {
        $carmodelprice = pricing_by_car_models::where('PricingID', "=", $id)->first();
        $vehicletype = VehicleType::all();
        $brand = Brand::all();
        $carmodel = CarModel::all();
        $selectedcarmodel = CarModel::where('id', "=",$carmodelprice->CarModelID)->first();
        return view("addon.edit")->with("carmodelprice", $carmodelprice)->with('vehicletype',$vehicletype)->with('brand',$brand)->with('carmodel',$carmodel)->with('selectedcarmodel',$selectedcarmodel);
    }

    public function UpdateCarModelPrice(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'vehicle_id' => 'required',
            'brand' => 'required',
            'carmodel_id' => 'required',
            'hours' => 'required',
            'kms' => 'required',
            'price' => 'required'

        ], $messages = [
            'vehicle_id.required' => 'The Vehicle Type field is required!',
            'brand.required' => 'The Vehicle Brand field is required!',
            'carmodel_id.required' => 'The Car Model field is required!',
            'hours.required' => 'The Hours field is required!',
            'kms.required' => 'The Kilometers field is required!',
            'price.required' => 'The Price field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('addon/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $carmodelprice = pricing_by_car_models::where('PricingID', $id)->first();
        $CarModelID = $request->input('carmodel_id');
        $Price = $request->input('price');
        $Status = $request->input('status') ? 'yes' : 'no';
        $hours = $request->input('hours');
        $kms = $request->input('kms');

        $carmodelprice->save();
        if ($carmodelprice) {
           
            DB::table('pricing_by_car_models')
            ->where('PricingID', $id)
            ->update([
                'CarModelID' => $CarModelID,
                'hours' => $hours,
                'kms' => $kms,
                'Price' => $Price,
                'Status' => $Status,
            ]);
        }

        return redirect('addon');
    }

    public function deleteCarModelPrice($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    // $bookingtypeModel = bookingtypes::find($id[$i]);
                    // $bookingtypeModel->delete();
                    $carmodelprice = pricing_by_car_models::where('PricingID', $id[$i])->first();
                    if($carmodelprice)
                    {
                        DB::table('pricing_by_car_models')
                        ->where('PricingID', $id)
                        ->delete();
                    }
                }

            } else {
                $carmodelprice = pricing_by_car_models::where('PricingID', $id)->first();
                if($carmodelprice)
                {
                    DB::table('pricing_by_car_models')
                    ->where('PricingID', $id)
                    ->delete();
                }
                
            }

        }

        return redirect()->back();
    }

    public function toggalSwitch(Request $request){
        $ischeck=$request->input('ischeck');
        $id=$request->input('id');
        
        $carmodelprice = pricing_by_car_models::where('PricingID', $id)->first();
        if($carmodelprice)
        {
            
            DB::table('pricing_by_car_models')
            ->where('PricingID', $id)
            ->update([
                'Status' => $ischeck=="true" ? 'yes' : 'no',
            ]);
            
        }

    }

    public function filter(Request $request)
    {
        $vehicletypeId = $request->input('vehicletype_id');
        $brandId = $request->input('brand_id');
        $carmodel = CarModel::where('vehicle_type_id', $vehicletypeId)->where('brand_id',$brandId)->get();
        return response()->json($carmodel);
    }
}
