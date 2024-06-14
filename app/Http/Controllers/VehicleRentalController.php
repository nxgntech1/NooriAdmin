<?php

namespace App\Http\Controllers;
use App\Models\Currency;

use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleRentalController extends Controller
{


    public function vehicleRent(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'vehicle_type') {
            $search = $request->input('search');
            $rentals = DB::table('tj_location_vehicule')
                ->join('tj_type_vehicule_rental', 'tj_type_vehicule_rental.id', '=', 'tj_location_vehicule.id_vehicule_rental')
                ->join('tj_user_app', 'tj_user_app.id', '=', 'tj_location_vehicule.id_user_app')
                ->select('tj_location_vehicule.*', 'tj_type_vehicule_rental.libelle', 'tj_user_app.prenom')
                ->where('tj_type_vehicule_rental.libelle', 'LIKE', '%' . $search . '%')
                ->orderBy('tj_location_vehicule.creer','desc')
                ->paginate(10);
                
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'customer') {
            $search = $request->input('search');
            $rentals = DB::table('tj_location_vehicule')
                ->join('tj_type_vehicule_rental', 'tj_type_vehicule_rental.id', '=', 'tj_location_vehicule.id_vehicule_rental')
                ->join('tj_user_app', 'tj_user_app.id', '=', 'tj_location_vehicule.id_user_app')
                ->select('tj_location_vehicule.*', 'tj_type_vehicule_rental.libelle', 'tj_user_app.prenom')
                ->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                ->orderBy('tj_location_vehicule.creer', 'desc')
                ->paginate(10);
        } else {
            $rentals = DB::table('tj_location_vehicule')
                ->join('tj_type_vehicule_rental', 'tj_type_vehicule_rental.id', '=', 'tj_location_vehicule.id_vehicule_rental')
                ->join('tj_user_app', 'tj_user_app.id', '=', 'tj_location_vehicule.id_user_app')
                ->select('tj_location_vehicule.*', 'tj_type_vehicule_rental.libelle', 'tj_user_app.prenom')
                ->orderBy('tj_location_vehicule.creer', 'desc')
                ->paginate(10);
        }
        
        return view("vehicle.vehicle-rent")->with("rentals", $rentals);
    }

    public function delete($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = VehicleLocation::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = VehicleLocation::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }


    public function show($id)
    {
        DB::enableQueryLog();
        
        $rentals = DB::table('tj_location_vehicule')
            ->join('tj_type_vehicule_rental', 'tj_type_vehicule_rental.id', '=', 'tj_location_vehicule.id_vehicule_rental')
            ->join('tj_user_app', 'tj_user_app.id', '=', 'tj_location_vehicule.id_user_app')
            ->select('tj_type_vehicule_rental.libelle', 'tj_type_vehicule_rental.image', 'tj_location_vehicule.*','tj_type_vehicule_rental.prix')
            ->addSelect('tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.email as user_email')
            ->where('tj_location_vehicule.id', $id)->first();
            $currency = Currency::where('statut', 'yes')->first();
        return view("vehicle.show")->with("rentals", $rentals)->with("currency", $currency);
    }


    public function ChangeStatus(Request $request,$id)
    {
        // print_r($request);
        // exit;
        $status = $request->input('statut');
        $user = VehicleLocation::find($id);
        if ($user) {
            $user->statut = $status;
        }
        $user->save();
        $data['data'] = 'Status updated Succesfully';
        return response()->json($data);
    }
}
