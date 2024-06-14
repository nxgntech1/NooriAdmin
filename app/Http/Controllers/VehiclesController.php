<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Driver;
use App\Models\Requests;
use App\Models\ParcelOrder;
use App\Models\Vehicle;
use App\Models\DriversDocuments;
use App\Models\Message;
use App\Models\Note;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\VehicleType;
use App\Models\bookingtypes;
use App\Models\vehicleImages;
use App\Models\pricing_by_car_models;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class VehiclesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index(Request $request)
    {

        $query = DB::table('tj_vehicule')
            ->leftJoin('brands', 'tj_vehicule.brand', '=', 'brands.id')
            ->leftJoin('tj_type_vehicule', 'tj_type_vehicule.id', '=', 'tj_vehicule.id_type_vehicule')
            ->leftJoin('car_model', 'tj_vehicule.model', '=', 'car_model.id')
            ->select('tj_vehicule.*', 'tj_type_vehicule.libelle','brands.name as BrandName','car_model.name as Model');
        
    	if($request->search != '' && $request->selected_search != '') {
    		$keyword = $request->input('search');
			$field = $request->input('selected_search');
			if($field == "brand"){
				$query->where('brands.name', 'LIKE', '%' . $keyword . '%');
			}
            elseif($field=="vehicletype")
            {
                $query->where('tj_type_vehicule.libelle', 'LIKE', '%' . $keyword . '%');
            }
            else
            {
                $query->where('car_model.name', 'LIKE', '%' . $keyword . '%');
            }
           
			$query->where('tj_vehicule.deleted_at', '=', NULL);
            $query->paginate(20);
		}

		$vehicles = $query->orderBy('tj_vehicule.id','desc')->paginate(20);
        //echo json_encode($vehicles, JSON_PRETTY_PRINT);
        return view("vehicles.index")->with("vehicles", $vehicles);
    }

    public function create()
    {
        $vehicleType = VehicleType::all();
        $brand = Brand::all();
        $carmodel = CarModel::all();
        return view("vehicles.create")->with('vehicleType',$vehicleType)->with('brand',$brand)->with('carmodel',$carmodel);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'id_type_vehicule'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'km'=>'required',
            'milage'=>'required',
            'car_number'=>'required',
            'color'=>'required',
            'passenger'=>'required',
            
            
            
        ], $messages = [
            'id_type_vehicule.required' => 'The Vehicle type field is required!',
            'brand.required' => 'The brand field is required!',
            'model.required' => 'The model field is required!',
            'km.required' => 'The km field is required!',
            'milage.required' => 'The milage field is required!',
            'car_number.required' => 'The NumberPlate field is required!',
            'color.required' => 'The Color field is required!',
            'passenger.required' => 'The Number of Passenger field is required!',
            
        ]);

        if ($validator->fails()) {
            return redirect('vehicles/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        
        
        // if ($request->hasfile('photo')) {
        //     $file = $request->file('photo');
        //     $extenstion = $file->getClientOriginalExtension();
        //     $time = time() . '.' . $extenstion;
        //     $filename = 'driver_image_' . $time;
        //     $path = public_path('assets/images/driver/') . $filename;
        //     Image::make($file->getRealPath())->resize(150, 150)->save($path);

        //     //$file->move(public_path('assets/images/driver'), $filename);
        //     $image = str_replace('data:image/png;base64,', '', $file);
        //     $image = str_replace(' ', '+', $image);
        //     $user->photo_path = $filename;
        // }

        $vehicle = new Vehicle;
        $vehicle->brand = $request->input('brand');
        $vehicle->model = $request->input('model');
        $vehicle->color = $request->input('color');
        $vehicle->numberplate = $request->input('car_number');
        $vehicle->car_make = '';
        $vehicle->km = $request->input('km');
        $vehicle->milage = $request->input('milage');
        $vehicle->statut = 'yes';
        $vehicle->creer = date('Y-m-d H:i:s');
        $vehicle->modifier = date('Y-m-d H:i:s');
        $vehicle->updated_at = date('Y-m-d H:i:s');
        $vehicle->id_type_vehicule = $request->input('id_type_vehicule');
        $vehicle->passenger = $request->input('passenger');
        $vehicle->save();
        $vehicle_id = $vehicle->id;

        $images = $request->file('images');
        
        if ($request->hasFile('images')) {
            if (!File::exists(public_path('assets/images/vehicle'))) {
                File::makeDirectory(public_path('assets/images/vehicle'), 0755, true);
            }
            $first = true;
            foreach ($images as $image) {
                $vehicle_image = new vehicleImages;
                $extenstion = $image->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'vehicle_' . $time;
                $path = $image->move(public_path('assets/images/vehicle'), $filename);
                chmod($path, 0755);

                //$image->storeAs('assets/images/vehicle', $filename);
                $vehicle_image->id_vehicle = $vehicle_id;
                $vehicle_image->image =$filename;
                $vehicle_image->creer = date('Y-m-d H:i:s');
                $vehicle_image->modifier = date('Y-m-d H:i:s');
                $vehicle_image->save();
                if($first)
                {
                    $vehiclenew = Vehicle::where('id',$vehicle_id)->first();
                    if($vehiclenew)
                    {
                        $vehiclenew->primary_image_id=$filename;
                        $vehiclenew->save();
                    }
                }
        
            }
        }

         return redirect('vehicles');
    }

    public function edit($id)
    {
       
        $vehicle = Vehicle::where('id', "=", $id)->first();
        $carmodel = [];
        if(!empty($vehicle)){
            $carmodel = Carmodel::where('brand_id', "=", $vehicle->brand)->where('vehicle_type_id', "=", $vehicle->id_type_vehicule)->get();

        }

        $vehicleImage = vehicleImages::where('id_vehicle', '=', $vehicle->id)->get();
        $vehicletype = VehicleType::all();
        $brand = Brand::all();
        $carmodel = CarModel::all();
        //echo json_encode($vehicleImage, JSON_PRETTY_PRINT);
        return view("vehicles.edit")->with("vehicle", $vehicle)->with('carmodel', $carmodel)->with('brand', $brand)
        ->with('vehicletype', $vehicletype)
        ->with('vehicleImage', $vehicleImage);
    }

    public function UpdateVehicle(Request $request, $id)
    {
        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
            $doc_validation = "mimes:doc,pdf,docx,zip,txt";
        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
            $doc_validation = "required|mimes:doc,pdf,docx,zip,txt";

        }

        $validator = Validator::make($request->all(), $rules = [
            'id_type_vehicule'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'km'=>'required',
            'milage'=>'required',
            'numberplate'=>'required',
            'color'=>'required',
            'passenger'=>'required',
            
        ], $messages = [
            'id_type_vehicule.required' => 'The Vehicle type field is required!',
            'brand.required' => 'The brand field is required!',
            'model.required' => 'The model field is required!',
            'km.required' => 'The km field is required!',
            'milage.required' => 'The milage field is required!',
            'numberplate.required' => 'The NumberPlate field is required!',
            'color.required' => 'The Color field is required!',
            'passenger.required' => 'The Number of Passenger field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        
        $status = $request->input('statut');
        $id_type_vehicule = $request->input('id_type_vehicule');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $color = $request->input('color');
        $km = $request->input('km');
        $milage = $request->input('milage');
        $numberplate = $request->input('numberplate');
        $passenger = $request->input('passenger');
        
        
        if ($status == "on") {
            $status = "yes";
        } else {
            $status = "no";
        }

       
        $vehicle = Vehicle::where('id', "=", $id)->first();
        if ($vehicle) {
            $vehicle->id_type_vehicule = $id_type_vehicule;
            $vehicle->brand = $brand;
            $vehicle->model = $model;
            $vehicle->color = $color;
            $vehicle->km = $km;
            $vehicle->milage = $milage;
            $vehicle->numberplate = $numberplate;
            $vehicle->passenger = $passenger;
            
            $vehicle->save();
        }else{
            $vehicle = new Vehicle;
            $vehicle->id_type_vehicule = $id_type_vehicule;
            $vehicle->brand = $brand;
            $vehicle->model = $model;
            $vehicle->color = $color;
            $vehicle->km = $km;
            $vehicle->milage = $milage;
            $vehicle->numberplate = $numberplate;
            $vehicle->passenger = $passenger;
            $vehicle->id_conducteur = $id;
            $vehicle->car_make = '';
            $vehicle->statut = 'yes';
            $vehicle->creer = date('Y-m-d H:i:s');
            $vehicle->modifier = date('Y-m-d H:i:s');
            $vehicle->updated_at = date('Y-m-d H:i:s');

            $vehicle->save();

        }
        $images = $request->file('images');
        
        if ($request->hasFile('images')) {
            if (!File::exists(public_path('assets/images/vehicle'))) {
                File::makeDirectory(public_path('assets/images/vehicle'), 0755, true);
            }
            $first = true;
            foreach ($images as $image) {
                $vehicle_image = new vehicleImages;
                $extenstion = $image->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'vehicle_' . $time;
                $path = $image->move(public_path('assets/images/vehicle'), $filename);
                chmod($path, 0755);

                //$image->storeAs('assets/images/vehicle', $filename);
                $vehicle_image->id_vehicle = $id;
                $vehicle_image->image =$filename;
                $vehicle_image->creer = date('Y-m-d H:i:s');
                $vehicle_image->modifier = date('Y-m-d H:i:s');
                $vehicle_image->save();
                if($first)
                {
                    $vehiclenew = Vehicle::where('id',$id)->first();
                    if($vehiclenew)
                    {
                        $vehiclenew->primary_image_id=$filename;
                        $vehiclenew->save();
                    }
                }
        
            }
        }
        return redirect('vehicles');
    }

    public function deletevehicle($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    
                    $vehicle = DB::table('tj_vehicule')->where('id', $id[$i])->first();
                    if($vehicle)
                    {
                        DB::table('tj_vehicule')
                        ->where('id', $id)
                        ->delete();
                    }
                }

            } else {
                $vehicle = DB::table('tj_vehicule')->where('id', $id)->first();
                if($vehicle)
                {
                    DB::table('tj_vehicule')
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
        
        $carmodelprice = DB::table('tj_vehicule')->where('id', $id)->first();
        if($carmodelprice)
        {
            DB::table('tj_vehicule')
            ->where('id', $id)
            ->update([
                'statut' => $ischeck=="true" ? 'yes' : 'no',
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

    public function deletevehicleImage($id)
    {
       
        $vehicleImage = DB::table('tj_vehicle_images')->where('id', $id)->first();
        if($vehicleImage)
        {
            $imagePath = public_path('assets/images/vehicle/' . $vehicleImage->image);
            
            // Delete the image file from the public directory
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            //Storage::delete(public_path('public/assets/images/vehicle/') . $vehicleImage->image);

            DB::table('tj_vehicle_images')
            ->where('id', $id)
            ->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
