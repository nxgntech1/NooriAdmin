<?php

namespace App\Http\Controllers;

use App\Models\RentalVehicleType;
use App\Models\VehicleRental;
use App\Models\VehicleLocation;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;
class VehicleTypeRentalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $currency = Currency::where('statut', 'yes')->first();
        
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'libelle') {

            $search = $request->input('search');
            $vehicletype = DB::table('tj_type_vehicule_rental')
                ->where('tj_type_vehicule_rental.libelle', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } elseif ($request->has('search') && $request->search != '' && $request->selected_search == 'prix') {

            $search = $request->input('search');
            $vehicletype = DB::table('tj_type_vehicule_rental')
                ->where('tj_type_vehicule_rental.prix', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } else {

            $vehicletype = RentalVehicleType::paginate(10);

        }

        return view('rental_vehicle_type.index')->with("vehicletype", $vehicletype)->with('currency',$currency);
    }

    public function create()
    {

        return view('rental_vehicle_type.create');
    }

    public function store(Request $request)
    {
        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";

        }

        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',
            'prix' => 'required',
            'image' => $image_validation,
             'no_of_passenger'=>'required',
        ], $messages = [
            'libelle.required' => 'The Vehicle Type field is required!',
            'prix.required' => 'The price field is required!',
            'image.required' => 'The Image field is required!',
             'no_of_passenger.required'=>'No Of Passenger field is required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $vehicle = new RentalVehicleType;
        $vehicle->libelle = $request->input('libelle');
        $vehicle->prix = $request->input('prix');
        $vehicle->no_of_passenger = $request->input('no_of_passenger');
        $vehicle->nb_place = $request->input('nb_place');
        $vehicle->nombre = $request->input('nombre');
        $status = $request->input('status');
        $vehicle->status="yes";

        if($status==''){
          $vehicle->status='no';
        }
        
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'image_vehicleRentalType' . $time;
            $path = public_path('assets/images/type_vehicle_rental/') . $filename;
            Image::make($file->getRealPath())->resize(100, 100)->save($path,100);

           // $file->move(public_path('assets/images/type_vehicle_rental'), $filename);
            $vehicle->image = $filename;
        }

        $vehicle->creer = date('Y-m-d H:i:s');
        $vehicle->modifier = date('Y-m-d H:i:s');

        $vehicle->save();
        return redirect('vehicle-rental-type/index');
    }

    public function edit($id)
    {

        $type = RentalVehicleType::find($id);

        return view("rental_vehicle_type.edit")->with("type", $type);
    }

    public function update(Request $request, $id)
    {
        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";

        }

        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',
            'prix' => 'required',
            'image' => $image_validation,
            'no_of_passenger'=>'required',
        ], $messages = [
            'libelle.required' => 'The Vehicle Type field is required!',
            'prix.required' => 'The price field is required!',
            'image.required' => 'The Image field is required!',
            'no_of_passenger.required' => 'The Number of Passenger field is required!'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $Libelle = $request->input('libelle');
        $prix = $request->input('prix');

        $no_of_passenger = $request->input('no_of_passenger');
        $nb_place = $request->input('nb_place');
        $nombre = $request->input('nombre');
        $status = $request->input('status');

        $status='yes';
        if($status==''){
          $status='no';
        }

        $modifier = $request->updated_at = date('Y-m-d H:i:s');
        $updated_at = $request->updated_at = date('Y-m-d H:i:s');

        $vehicle = RentalVehicleType::find($id);
        if ($vehicle) {
            $vehicle->Libelle = $Libelle;
            $vehicle->prix = $prix;
            $vehicle->status=$status;
            $vehicle->no_of_passenger=$no_of_passenger;
            $vehicle->nb_place = $nb_place;
            $vehicle->nombre = $nombre;
            $vehicle->modifier = $modifier;
            $vehicle->updated_at = $updated_at;
            if ($request->hasfile('image')) {
                $destination = public_path('assets/images/type_vehicle_rental/' . $vehicle->image);
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'image_vehicleType' . $time;
                $path = public_path('assets/images/type_vehicle_rental/') . $filename;
                Image::make($file->getRealPath())->resize(150, 150)->save($path, 100);
                /*Image::make($file->getRealPath())->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);*/

                //$file->move(public_path('assets/images/type_vehicle_rental'), $filename);
                $vehicle->image = $filename;
            }
            $vehicle->save();
            return redirect('vehicle-rental-type/index');
        }

    }


    public function delete($id)
    {
        
        if ($id != "") {
            
            $id = json_decode($id);
           
            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {

                    $location = VehicleLocation::where('id_vehicule_rental', $id[$i]);
                    if ($location) {
                        $location->delete();
                    }
                    $rental = VehicleRental::where('id_type_vehicule_rental', $id[$i]);
                    if ($rental) {
                        $rental->delete();
                    }

                   
                    $user = RentalVehicleType::find($id[$i]);
                    $user->delete();

                }

            } else {
               
                $location = VehicleLocation::where('id_vehicule_rental', $id);
                if($location){
                    $location->delete();

                }
                    
                $user = RentalVehicleType::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function toggalSwitch(Request $request)
       {
           $ischeck = $request->input('ischeck');
           $id = $request->input('id');
           $vehicle = RentalVehicleType::find($id);

           if ($ischeck == "true") {
               $vehicle->status = 'yes';
           } else {
               $vehicle->status = 'no';
           }
           $vehicle->save();

       }

}
